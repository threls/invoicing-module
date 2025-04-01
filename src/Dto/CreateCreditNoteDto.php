<?php

namespace Threls\ThrelsInvoicingModule\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Threls\ThrelsInvoicingModule\Enums\CreditNoteStatusEnum;

#[MapName(SnakeCaseMapper::class)]
class CreateCreditNoteDto extends Data
{
    public function __construct(
        public readonly int $transactionId,
        public readonly int $amount,
        public readonly string $currency,
        public readonly ?string $reason,
        public readonly CreditNoteStatusEnum $status,
        public readonly ?string $statusReason = null,
    )
    {
    }

}
