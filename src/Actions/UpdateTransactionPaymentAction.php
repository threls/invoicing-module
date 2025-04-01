<?php

namespace Threls\ThrelsInvoicingModule\Actions;

use Threls\ThrelsInvoicingModule\Dto\UpdateTransactionPaymentDto;
use Threls\ThrelsInvoicingModule\Models\Transaction;

class UpdateTransactionPaymentAction
{
    public function execute(UpdateTransactionPaymentDto $updateTransactionPaymentDto): void
    {
        Transaction::find($updateTransactionPaymentDto->transactionId)->update([
            'payment_id' => $updateTransactionPaymentDto->paymentId,
            'payment_type' => $updateTransactionPaymentDto->paymentType,
        ]);


    }

}
