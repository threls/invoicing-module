<?php

namespace Threls\ThrelsInvoicingModule\Actions;

use Threls\ThrelsInvoicingModule\Dto\CreateInvoiceDto;
use Threls\ThrelsInvoicingModule\Models\Transaction;

class CreateInvoiceAction
{
    public function execute(CreateInvoiceDto $createInvoiceDto)
    {
        /** @var Transaction $transaction */
        $transaction = Transaction::find($createInvoiceDto->transactionId);

        return $transaction->invoice()->create([
            'vat_amount' => $createInvoiceDto->vatAmount,
            'total_amount' => $createInvoiceDto->totalAmount,
            'currency' => $createInvoiceDto->currency,
        ]);
    }
}
