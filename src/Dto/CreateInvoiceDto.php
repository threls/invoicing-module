<?php

namespace Threls\ThrelsInvoicingModule\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class CreateInvoiceDto extends Data
{
    public function __construct(
        public readonly int $transactionId,
        public readonly int $vatAmount,
        public readonly int $totalAmount,
        public readonly string $currency,
    ) {}
}
