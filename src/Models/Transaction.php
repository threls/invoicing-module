<?php

namespace Threls\ThrelsInvoicingModule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\ModelStatus\HasStatuses;
use Threls\ThrelsInvoicingModule\Enums\TransactionTypeEnum;

class Transaction extends Model
{
    use SoftDeletes;
    use HasStatuses;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'type' => TransactionTypeEnum::class,
        ];
    }

    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function payment(): MorphTo
    {
        return $this->morphTo();
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function creditNote(): HasOne
    {
        return $this->hasOne(CreditNote::class);
    }

}
