<?php

namespace Threls\ThrelsInvoicingModule\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class LinkTransactionWithPaymentDto extends Data
{
    public function __construct(
        public int $transactionId,
        public int $paymentableId,
        public string $paymentableType
    ) {}
}
