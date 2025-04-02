<?php

namespace Threls\ThrelsInvoicingModule\Models;

use Brick\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\ModelStatus\HasStatuses;
use Threls\ThrelsInvoicingModule\Casts\MoneyCast;
use Threls\ThrelsInvoicingModule\Enums\TransactionTypeEnum;

/**
 * @property Money|null $amount
 * @property Money|null $vat_amount
 */
class Transaction extends Model
{
    use HasStatuses;
    use SoftDeletes;

    protected $guarded = ['id'];

    public Money|null $amount;

    protected function casts(): array
    {
        return [
            'type' => TransactionTypeEnum::class,
            'amount' => MoneyCast::class,
            'vat_amount' => MoneyCast::class,
        ];
    }

    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function creditNote(): HasOne
    {
        return $this->hasOne(CreditNote::class);
    }

    public function payment(): MorphOne
    {
        return $this->morphOne(TransactionPayment::class, 'paymentable');
    }
}
