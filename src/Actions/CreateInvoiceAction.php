<?php

namespace Threls\ThrelsInvoicingModule\Actions;

use LaravelDaily\Invoices\Traits\SerialNumberFormatter;
use Threls\ThrelsInvoicingModule\Dto\CreateInvoiceDto;
use Threls\ThrelsInvoicingModule\Models\Invoice;
use Threls\ThrelsInvoicingModule\Models\Transaction;

class CreateInvoiceAction
{
    protected Transaction $transaction;

    protected Invoice $invoice;

    protected CreateInvoiceDto $createInvoiceDto;

    use SerialNumberFormatter;

    public function execute(Transaction $transaction, CreateInvoiceDto $createInvoiceDto)
    {
        $this->transaction = $transaction;
        $this->createInvoiceDto = $createInvoiceDto;

        $this->createInvoice();

        return $this->invoice;
    }

    protected function createInvoice()
    {
        $this->invoice = $this->transaction->invoice()->create([
            'vat_amount' => $this->createInvoiceDto->vatAmount,
            'total_amount' => $this->createInvoiceDto->totalAmount,
            'currency' => $this->createInvoiceDto->currency,
        ]);

        $this->invoice->update([
            'invoice_number' => $this->createInvoiceNumber(),
        ]);

        return $this;

    }

    protected function createInvoiceNumber(): string
    {
        $this->series(config('invoicing-module.serial_number.series'));
        $this->sequence($this->invoice->id);
        $this->sequencePadding(config('invoicing-module.serial_number.sequence_padding'));
        $this->delimiter(config('invoicing-module.serial_number.delimiter'));
        $this->serialNumberFormat(config('invoicing-module.serial_number.format'));

        return $this->getSerialNumber();
    }
}
