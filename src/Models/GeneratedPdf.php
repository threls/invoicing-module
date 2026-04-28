<?php

namespace Threls\ThrelsInvoicingModule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Threls\ThrelsInvoicingModule\Enums\GeneratedPdfStatusEnum;

class GeneratedPdf extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => GeneratedPdfStatusEnum::class,
        ];
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
