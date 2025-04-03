<?php

namespace Threls\ThrelsInvoicingModule\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class CreateTransactionItemDto extends Data
{
    public function __construct(
        public readonly string $modelType,
        public readonly int $modelId,
        public readonly string $description,
        public readonly int $qty,
        public readonly int $amount,
        public readonly int $totalAmount,
        public readonly string $currency,
        public readonly int $vatId,
    ) {}
}
