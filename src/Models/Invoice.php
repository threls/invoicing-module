<?php

namespace Threls\ThrelsInvoicingModule\Models;

use Brick\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Threls\ThrelsInvoicingModule\Casts\MoneyCast;
use Threls\ThrelsInvoicingModule\InvoicingModelResolverManager;

/**
 * @property Money|null $total_amount
 * @property Money|null $vat_amount
 */
class Invoice extends Model implements HasMedia
{
    use InteractsWithMedia, SoftDeletes;

    protected $guarded = ['id'];

    public const string MEDIA_INVOICE = 'invoice';

    protected function casts(): array
    {
        return [
            'vat_amount' => MoneyCast::class,
            'total_amount' => MoneyCast::class,
        ];
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(InvoicingModelResolverManager::getModelClass('transaction'));
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MEDIA_INVOICE)->singleFile();
    }

    public function creditNote(): HasOne
    {
        return $this->hasOne(InvoicingModelResolverManager::getModelClass('creditNote'), 'invoice_id', 'id');
    }
}
