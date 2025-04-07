<?php

namespace Threls\ThrelsInvoicingModule\Traits;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Threls\ThrelsInvoicingModule\Models\TransactionItem;

trait IsTransactionable
{
    public function transactionItem(): MorphOne
    {
        return $this->morphOne(TransactionItem::class, 'model');
    }
}
