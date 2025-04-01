<?php

namespace Threls\ThrelsInvoicingModule\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class UpdateTransactionPaymentDto extends Data
{
    public function __construct(
        public int $transactionId,
        public int $paymentId,
        public string $paymentType
    ) {}
}
