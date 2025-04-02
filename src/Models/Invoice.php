<?php

namespace Threls\ThrelsInvoicingModule\Models;

use Brick\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Threls\ThrelsInvoicingModule\Casts\MoneyCast;

/**
 * @property Money|null $total_amount
 * @property Money|null $vat_amount
 */
class Invoice extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'vat_amount' => MoneyCast::class,
            'total_amount' => MoneyCast::class,
        ];
    }
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
