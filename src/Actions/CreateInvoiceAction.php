<?php

namespace Threls\ThrelsInvoicingModule\Actions;

use Threls\ThrelsInvoicingModule\Dto\CreateInvoiceDto;
use Threls\ThrelsInvoicingModule\Models\Invoice;
use Threls\ThrelsInvoicingModule\Models\Transaction;

class CreateInvoiceAction
{
    protected Transaction $transaction;

    protected Invoice $invoice;

    protected CreateInvoiceDto $createInvoiceDto;

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

        return $this;

    }
}
