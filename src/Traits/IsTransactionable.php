<?php

namespace Threls\ThrelsInvoicingModule\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Threls\ThrelsInvoicingModule\Models\TransactionItem;

trait IsTransactionable
{
    public function transactionItems(): MorphMany
    {
        return $this->morphMany(TransactionItem::class, 'model');
    }

    public function transactionItem(): MorphOne
    {
        return $this->morphOne(TransactionItem::class, 'model');
    }
}
