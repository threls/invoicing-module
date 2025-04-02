<?php

namespace Threls\ThrelsInvoicingModule\Actions;

use Threls\ThrelsInvoicingModule\Dto\LinkTransactionWithPaymentDto;
use Threls\ThrelsInvoicingModule\Models\Transaction;

class LinkTransactionWithPaymentAction
{
    public function execute(LinkTransactionWithPaymentDto $updateTransactionPaymentDto): void
    {
        Transaction::find($updateTransactionPaymentDto->transactionId)->payment()->create([
            'paymentable_id' => $updateTransactionPaymentDto->paymentableId,
            'paymentable_type' => $updateTransactionPaymentDto->paymentableType,
        ]);

    }
}
