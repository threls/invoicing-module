<?php

namespace Threls\ThrelsInvoicingModule\Models;

use Brick\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Threls\ThrelsInvoicingModule\Casts\MoneyCast;
use Threls\ThrelsInvoicingModule\InvoicingModelResolverManager;

/**
 * @property Money|null $amount
 * @property Money|null $vat_amount
 * @property Money|null $total_amount
 */
class TransactionItem extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'amount' => MoneyCast::class,
            'total_amount' => MoneyCast::class,
            'vat_amount' => MoneyCast::class,
        ];
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(InvoicingModelResolverManager::getModelClass('transaction'));
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function vat(): BelongsTo
    {
        return $this->belongsTo(VatRate::class, 'vat_id');
    }
}
