<?php

namespace Threls\ThrelsInvoicingModule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\ModelStatus\HasStatuses;

class CreditNote extends Model
{
    use SoftDeletes;
    use HasStatuses;

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
