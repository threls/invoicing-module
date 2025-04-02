<?php

namespace Threls\ThrelsInvoicingModule\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Threls\ThrelsInvoicingModule\Enums\CreditNoteStatusEnum;

#[MapName(SnakeCaseMapper::class)]
class UpdateCreditNoteStatusDto extends Data
{
    public function __construct(
        public int $creditNoteId,
        public CreditNoteStatusEnum $status,
        public ?string $reason = null,
    ) {}
}
