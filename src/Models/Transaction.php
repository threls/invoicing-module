<?php

namespace Threls\ThrelsInvoicingModule\Models;

use Brick\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\ModelStatus\HasStatuses;
use Threls\ThrelsInvoicingModule\Casts\MoneyCast;
use Threls\ThrelsInvoicingModule\Enums\TransactionTypeEnum;
use Threls\ThrelsInvoicingModule\InvoicingModelResolverManager;

/**
 * @property Money|null $amount
 * @property Money|null $vat_amount
 */
class Transaction extends Model
{
    use HasStatuses;
    use SoftDeletes;

    protected $guarded = ['id'];

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
        return $this->hasMany(InvoicingModelResolverManager::getModelClass('transactionItem'));
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(InvoicingModelResolverManager::getModelClass('invoice'));
    }

    public function creditNote(): HasOne
    {
        return $this->hasOne(InvoicingModelResolverManager::getModelClass('creditNote'));
    }

    public function payment(): MorphOne
    {
        return $this->morphOne(InvoicingModelResolverManager::getModelClass('transactionPayment'), 'paymentable');
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
