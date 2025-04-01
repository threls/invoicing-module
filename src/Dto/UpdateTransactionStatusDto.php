<?php

namespace Threls\ThrelsInvoicingModule\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Threls\ThrelsInvoicingModule\Enums\TransactionStatusEnum;

#[MapName(SnakeCaseMapper::class)]
class UpdateTransactionStatusDto extends Data
{
    public function __construct(
        public int $transactionId,
        public TransactionStatusEnum $status,
    )
    {
    }

}
