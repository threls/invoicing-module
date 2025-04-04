<?php

namespace Threls\ThrelsInvoicingModule\Actions;

use Threls\ThrelsInvoicingModule\Dto\CreateTransactionDto;
use Threls\ThrelsInvoicingModule\Dto\CreateTransactionItemDto;
use Threls\ThrelsInvoicingModule\Models\Transaction;
use Threls\ThrelsInvoicingModule\Models\VatRate;
use Threls\ThrelsInvoicingModule\Utils\CalculateVatUtil;

class CreateTransactionAction
{
    protected CreateTransactionDto $createTransactionDto;

    protected Transaction $transaction;

    public function __construct(protected readonly CalculateVatUtil $calculateVatUtil) {}

    public function execute(CreateTransactionDto $createTransactionDto): Transaction
    {
        $this->createTransactionDto = $createTransactionDto;

        $this->createTransaction()
            ->createTransactionItems()
            ->updateTransactionVat();

        return $this->transaction;

    }

    protected function createTransaction(): self
    {
        $this->transaction = Transaction::create([
            'user_id' => $this->createTransactionDto->userId,
            'amount' => $this->createTransactionDto->amount,
            'currency' => $this->createTransactionDto->currency,
            'type' => $this->createTransactionDto->type,
        ]);

        $this->transaction->setStatus($this->createTransactionDto->status->value);

        return $this;

    }

    protected function createTransactionItems()
    {
        $this->createTransactionDto->items->each(function (CreateTransactionItemDto $createTransactionItemDto) {

            /** @var VatRate $vatRate */
            $vatRate = VatRate::find($createTransactionItemDto->vatId);

            $this->transaction->transactionItems()->create(
                [
                    'model_id' => $createTransactionItemDto->modelId,
                    'model_type' => $createTransactionItemDto->modelType,
                    'description' => $createTransactionItemDto->description,
                    'qty' => $createTransactionItemDto->qty,
                    'amount' => $createTransactionItemDto->amount,
                    'currency' => $createTransactionItemDto->currency,
                    'vat_amount' => $this->calculateVatUtil->extractVAT($createTransactionItemDto->totalAmount, $vatRate->rate),
                    'total_amount' => $createTransactionItemDto->totalAmount,
                    'vat_id' => $createTransactionItemDto->vatId,
                ]
            );
        });

        return $this;
    }

    protected function updateTransactionVat()
    {
        $totalVat = (int) $this->transaction->transactionItems()->sum('vat_amount');
        $this->transaction->update(['vat_amount' => $totalVat]);
    }
}
