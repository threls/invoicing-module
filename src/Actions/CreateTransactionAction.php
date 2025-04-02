<?php

namespace Threls\ThrelsInvoicingModule\Actions;

use Threls\ThrelsInvoicingModule\Dto\CreateTransactionDto;
use Threls\ThrelsInvoicingModule\Dto\CreateTransactionItemDto;
use Threls\ThrelsInvoicingModule\Models\Transaction;

class CreateTransactionAction
{
    protected CreateTransactionDto $createTransactionDto;

    protected Transaction $transaction;

    public function execute(CreateTransactionDto $createTransactionDto): Transaction
    {
        $this->createTransactionDto = $createTransactionDto;

        $this->createTransaction()
            ->createTransactionItems();

        return $this->transaction;

    }

    protected function createTransaction(): self
    {
        $this->transaction = Transaction::create([
            'user_id' => $this->createTransactionDto->userId,
            'amount' => $this->createTransactionDto->amount,
            'currency' => $this->createTransactionDto->currency,
            'vat_amount' => $this->createTransactionDto->vatAmount,
            'type' => $this->createTransactionDto->type,
        ]);

        $this->transaction->setStatus($this->createTransactionDto->status->value);

        return $this;

    }

    protected function createTransactionItems()
    {
        $this->createTransactionDto->items->each(function (CreateTransactionItemDto $createTransactionItemDto) {

            $this->transaction->transactionItems()->create(
                [
                    'model_id' => $createTransactionItemDto->modelId,
                    'model_type' => $createTransactionItemDto->modelType,
                    'description' => $createTransactionItemDto->description,
                    'qty' => $createTransactionItemDto->qty,
                    'amount' => $createTransactionItemDto->amount,
                    'currency' => $createTransactionItemDto->currency,
                    'vat_amount' => $createTransactionItemDto->vatAmount,
                    'total_amount' => $createTransactionItemDto->totalAmount,
                    'vat_id' => $createTransactionItemDto->vatId,
                ]
            );
        });
    }
}
