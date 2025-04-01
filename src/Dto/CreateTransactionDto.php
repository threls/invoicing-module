<?php

namespace Threls\ThrelsInvoicingModule\Dto;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Threls\ThrelsInvoicingModule\Enums\TransactionStatusEnum;
use Threls\ThrelsInvoicingModule\Enums\TransactionTypeEnum;

#[MapName(SnakeCaseMapper::class)]
class CreateTransactionDto extends Data
{
    public function __construct(
        public readonly int $userId,
        public readonly TransactionTypeEnum $type,
        public readonly TransactionStatusEnum $status,
        public readonly int $amount,
        public readonly string $currency,
        public readonly int $vatAmount,
        public readonly Collection $items,
    ) {}
}
