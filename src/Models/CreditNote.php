<?php

namespace Threls\ThrelsInvoicingModule\Models;

use Brick\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\ModelStatus\HasStatuses;
use Threls\ThrelsInvoicingModule\Casts\MoneyCast;
use Threls\ThrelsInvoicingModule\Traits\IsTransactionable;

/**
 * @property Money|null $amount
 */
class CreditNote extends Model implements HasMedia
{
    use HasStatuses;
    use InteractsWithMedia;
    use IsTransactionable;
    use SoftDeletes;

    public const string MEDIA_CREDIT_NOTE = 'media_credit_note';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'amount' => MoneyCast::class,
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MEDIA_CREDIT_NOTE)->singleFile();
    }

    public function transaction(): Transaction
    {
        return $this->transactionItem->transaction;
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }
}
