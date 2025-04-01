<?php

namespace Threls\ThrelsInvoicingModule\Services;

use Threls\ThrelsInvoicingModule\Actions\CreateTransactionAction;
use Threls\ThrelsInvoicingModule\Actions\UpdateTransactionPaymentAction;
use Threls\ThrelsInvoicingModule\Actions\UpdateTransactionStatusAction;
use Threls\ThrelsInvoicingModule\Dto\CreateTransactionDto;
use Threls\ThrelsInvoicingModule\Dto\UpdateTransactionPaymentDto;
use Threls\ThrelsInvoicingModule\Dto\UpdateTransactionStatusDto;
use Threls\ThrelsInvoicingModule\Models\Transaction;

class TransactionService
{
    public function createTransaction(CreateTransactionDto $createTransactionDto): Transaction
    {
        return app(CreateTransactionAction::class)->execute($createTransactionDto);
    }

    public function updateTransactionPayment(UpdateTransactionPaymentDto $updateTransactionPaymentDto): void
    {
       app(UpdateTransactionPaymentAction::class)->execute($updateTransactionPaymentDto);
    }

    public function updateTransactionStatus(UpdateTransactionStatusDto $updateTransactionStatusDto): void
    {
        app(UpdateTransactionStatusAction::class)->execute($updateTransactionStatusDto);
    }

}
