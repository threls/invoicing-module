<?php

namespace Threls\ThrelsInvoicingModule\Actions;

use LaravelDaily\Invoices\Traits\SerialNumberFormatter;
use Threls\ThrelsInvoicingModule\Dto\CreateCreditNoteDto;
use Threls\ThrelsInvoicingModule\Models\CreditNote;
use Threls\ThrelsInvoicingModule\Models\Invoice;

class CreateCreditNoteAction
{
    use SerialNumberFormatter;

    protected CreditNote $creditNote;

    protected CreateCreditNoteDto $createCreditNoteDto;

    public function execute(CreateCreditNoteDto $createCreditNoteDto)
    {
        $this->createCreditNoteDto = $createCreditNoteDto;

        $this->createCreditNote();

        return $this->creditNote;

    }

    public function createCreditNote()
    {
        /** @var Invoice $invoice */
        $invoice = Invoice::find($this->createCreditNoteDto->invoiceId);

        $this->creditNote = $invoice->creditNote()->create([
            'amount' => $this->createCreditNoteDto->amount,
            'currency' => $this->createCreditNoteDto->currency,
            'reason' => $this->createCreditNoteDto->reason,
        ]);

        $this->creditNote->update([
            'credit_note_number' => $this->createCreditNoteNumber(),
        ]);

        $this->creditNote->setStatus($this->createCreditNoteDto->status->value, $this->createCreditNoteDto->statusReason);

        return $this;

    }

    protected function createCreditNoteNumber(): string
    {
        $this->series(config('invoicing-module.serial_number.series'));
        $this->sequence($this->creditNote->id);
        $this->sequencePadding(config('invoicing-module.serial_number.sequence_padding'));
        $this->delimiter(config('invoicing-module.serial_number.delimiter'));
        $this->serialNumberFormat(config('invoicing-module.serial_number.format'));

        return $this->getSerialNumber();
    }
}
