<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Threls\ThrelsInvoicingModule\Actions\GenerateCreditNotePDFAction;
use Threls\ThrelsInvoicingModule\Actions\GenerateInvoicePDFAction;
use Threls\ThrelsInvoicingModule\Dto\CreditNotePDFGenerationDto;
use Threls\ThrelsInvoicingModule\Dto\InvoicePDFGenerationDto;
use Threls\ThrelsInvoicingModule\Enums\GeneratedPdfStatusEnum;
use Threls\ThrelsInvoicingModule\Enums\TransactionTypeEnum;
use Threls\ThrelsInvoicingModule\Jobs\CreditNotePDFGenerationJob;
use Threls\ThrelsInvoicingModule\Jobs\InvoicePDFGenerationJob;
use Threls\ThrelsInvoicingModule\Models\CreditNote;
use Threls\ThrelsInvoicingModule\Models\GeneratedPdf;
use Threls\ThrelsInvoicingModule\Models\Invoice;
use Threls\ThrelsInvoicingModule\Models\Transaction;

uses(RefreshDatabase::class);

// ---------------------------------------------------------------------------
// Test-only subclasses that stub out the heavy PDF-generation chain so tests
// focus purely on the status-tracking behaviour we added.
// ---------------------------------------------------------------------------

class TestableInvoicePDFGenerationJob extends InvoicePDFGenerationJob
{
    protected function setSeller(): static { return $this; }

    protected function setCustomer(): static { return $this; }

    protected function setItems(): static { return $this; }

    protected function createInvoice(): static { return $this; }

    protected function addMediaToDisk(): static { return $this; }

    protected function dispatchCreationEvent(): void {}
}

class FailingInvoicePDFGenerationJob extends InvoicePDFGenerationJob
{
    protected function setSeller(): static
    {
        throw new \RuntimeException('Simulated PDF generation failure');
    }
}

class TestableCreditNotePDFGenerationJob extends CreditNotePDFGenerationJob
{
    protected function setSeller(): static { return $this; }

    protected function setCustomer(): static { return $this; }

    protected function setItems(): static { return $this; }

    protected function createInvoice(): static { return $this; }

    protected function addMediaToDisk(): void {}
}

class FailingCreditNotePDFGenerationJob extends CreditNotePDFGenerationJob
{
    protected function setSeller(): static
    {
        throw new \RuntimeException('Simulated credit note PDF failure');
    }
}

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------

function createInvoice(): Invoice
{
    $transaction = Transaction::create([
        'amount'   => 10000,
        'currency' => 'EUR',
        'type'     => TransactionTypeEnum::PURCHASE,
    ]);

    return Invoice::create([
        'transaction_id' => $transaction->id,
        'vat_amount'     => 0,
        'total_amount'   => 10000,
        'currency'       => 'EUR',
    ]);
}

function createCreditNote(): CreditNote
{
    $invoice = createInvoice();

    return CreditNote::create([
        'invoice_id' => $invoice->id,
        'amount'     => 10000,
        'currency'   => 'EUR',
    ]);
}

// ---------------------------------------------------------------------------
// Action tests — verify GeneratedPdf record creation and job dispatch
// ---------------------------------------------------------------------------

it('creates a pending generated_pdf record and dispatches job when generating an invoice pdf', function () {
    Bus::fake();

    $invoice = createInvoice();
    $dto = new InvoicePDFGenerationDto(
        name: 'Receipt',
        customerName: 'Test Customer',
        customerAddress: null,
        customerPhone: null,
        customerEmail: null,
        customerVAT: null,
    );

    app(GenerateInvoicePDFAction::class)->execute($invoice, $dto);

    $record = GeneratedPdf::first();

    expect(GeneratedPdf::count())->toBe(1)
        ->and($record->status)->toBe(GeneratedPdfStatusEnum::PENDING)
        ->and($record->model_type)->toBe(Invoice::class)
        ->and($record->model_id)->toBe($invoice->id);

    Bus::assertDispatched(InvoicePDFGenerationJob::class);
});

it('creates a pending generated_pdf record and dispatches job when generating a credit note pdf', function () {
    Bus::fake();

    $creditNote = createCreditNote();
    $dto = new CreditNotePDFGenerationDto(
        name: 'Credit Note',
        customerName: 'Test Customer',
        customerAddress: null,
        customerPhone: null,
        customerEmail: null,
        customerVAT: null,
    );

    app(GenerateCreditNotePDFAction::class)->execute($creditNote, $dto);

    $record = GeneratedPdf::first();

    expect(GeneratedPdf::count())->toBe(1)
        ->and($record->status)->toBe(GeneratedPdfStatusEnum::PENDING)
        ->and($record->model_type)->toBe(CreditNote::class)
        ->and($record->model_id)->toBe($creditNote->id);

    Bus::assertDispatched(CreditNotePDFGenerationJob::class);
});

// ---------------------------------------------------------------------------
// Job tests — verify status transitions during job execution
// ---------------------------------------------------------------------------

it('transitions invoice pdf status to succeeded when the job completes successfully', function () {
    $generatedPdf = GeneratedPdf::create(['status' => GeneratedPdfStatusEnum::PENDING]);

    $job = new TestableInvoicePDFGenerationJob(
        new Invoice(),
        new InvoicePDFGenerationDto(null, null, null, null, null, null),
        $generatedPdf,
    );

    $job->handle();

    expect($generatedPdf->fresh()->status)->toBe(GeneratedPdfStatusEnum::SUCCEEDED);
});

it('sets invoice pdf status to failed when the job throws an exception', function () {
    $generatedPdf = GeneratedPdf::create(['status' => GeneratedPdfStatusEnum::PENDING]);

    $job = new FailingInvoicePDFGenerationJob(
        new Invoice(),
        new InvoicePDFGenerationDto(null, null, null, null, null, null),
        $generatedPdf,
    );

    expect(fn () => $job->handle())->toThrow(\RuntimeException::class, 'Simulated PDF generation failure');
    expect($generatedPdf->fresh()->status)->toBe(GeneratedPdfStatusEnum::FAILED);
});

it('transitions credit note pdf status to succeeded when the job completes successfully', function () {
    $generatedPdf = GeneratedPdf::create(['status' => GeneratedPdfStatusEnum::PENDING]);

    $job = new TestableCreditNotePDFGenerationJob(
        new CreditNote(),
        new CreditNotePDFGenerationDto(null, null, null, null, null, null),
        $generatedPdf,
    );

    $job->handle();

    expect($generatedPdf->fresh()->status)->toBe(GeneratedPdfStatusEnum::SUCCEEDED);
});

it('sets credit note pdf status to failed when the job throws an exception', function () {
    $generatedPdf = GeneratedPdf::create(['status' => GeneratedPdfStatusEnum::PENDING]);

    $job = new FailingCreditNotePDFGenerationJob(
        new CreditNote(),
        new CreditNotePDFGenerationDto(null, null, null, null, null, null),
        $generatedPdf,
    );

    expect(fn () => $job->handle())->toThrow(\RuntimeException::class, 'Simulated credit note PDF failure');
    expect($generatedPdf->fresh()->status)->toBe(GeneratedPdfStatusEnum::FAILED);
});
