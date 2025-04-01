<?php

namespace Threls\ThrelsInvoicingModule\Actions;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Threls\ThrelsInvoicingModule\Dto\UpdateTransactionStatusDto;
use Threls\ThrelsInvoicingModule\Enums\TransactionStatusEnum;
use Threls\ThrelsInvoicingModule\Models\Transaction;

class UpdateTransactionStatusAction
{
    public function execute(UpdateTransactionStatusDto $updateTransactionStatusDto): void
    {
        /** @var Transaction $transaction */
        $transaction = Transaction::find($updateTransactionStatusDto->transactionId);

        /** @phpstan-ignore-next-line */
        if (! TransactionStatusEnum::from($transaction->status)->canTransitionTo($updateTransactionStatusDto->status)){
            throw new BadRequestHttpException('Transaction status cannot be transitioned to '.$updateTransactionStatusDto->status->value);
        }
        $transaction->setStatus($updateTransactionStatusDto->status->value);

    }

}
