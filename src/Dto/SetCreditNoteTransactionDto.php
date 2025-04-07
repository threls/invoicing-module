<?php

namespace Threls\ThrelsInvoicingModule\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Threls\ThrelsInvoicingModule\Models\CreditNote;
use Threls\ThrelsInvoicingModule\Models\Transaction;

#[MapName(SnakeCaseMapper::class)]
class SetCreditNoteTransactionDto extends Data
{
    public function __construct(
        public readonly CreditNote $creditNote,
        public readonly Transaction $transaction,
    ) {}
}
