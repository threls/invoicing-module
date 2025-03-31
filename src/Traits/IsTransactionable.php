<?php

namespace Threls\ThrelsInvoicingModule\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Threls\ThrelsInvoicingModule\Models\TransactionItem;

trait IsTransactionable
{
    public function transactionItems(): MorphMany
    {
        return $this->morphMany(TransactionItem::class, 'model');
    }
}
