<?php

namespace Threls\ThrelsInvoicingModule\Actions;

use Threls\ThrelsInvoicingModule\Dto\LinkTransactionWithPaymentDto;
use Threls\ThrelsInvoicingModule\Models\TransactionPayment;

class LinkTransactionWithPaymentAction
{
    public function execute(LinkTransactionWithPaymentDto $updateTransactionPaymentDto): void
    {
        TransactionPayment::create(
            [
                'transaction_id' => $updateTransactionPaymentDto->transactionId,
                'paymentable_id' => $updateTransactionPaymentDto->paymentableId,
                'paymentable_type' => $updateTransactionPaymentDto->paymentableType,
            ]
        );

    }
}
