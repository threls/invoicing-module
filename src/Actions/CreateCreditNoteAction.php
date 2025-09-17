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
            'vat_amount' => $this->createCreditNoteDto->vatAmount,
            'currency' => $this->createCreditNoteDto->currency,
            'reason' => $this->createCreditNoteDto->reason,
        ]);

        $this->creditNote->setStatus($this->createCreditNoteDto->status->value, $this->createCreditNoteDto->statusReason);

        return $this;

    }
}
