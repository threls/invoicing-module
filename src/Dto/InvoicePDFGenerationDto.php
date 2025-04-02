<?php

namespace Threls\ThrelsInvoicingModule\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class InvoicePDFGenerationDto extends Data
{
    public function __construct(
        public ?string $name,
        public ?string $customerName,
        public ?string $customerAddress,
        public ?string $customerPhone,
        public ?string $customerEmail,
    ) {}
}
