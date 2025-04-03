<?php

namespace Threls\ThrelsInvoicingModule\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionPayment extends Model
{
    protected $table = 'transaction_payments';

    protected $guarded = ['id'];
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function paymentable()
    {
        return $this->morphTo();
    }
}
