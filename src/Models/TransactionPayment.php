<?php

namespace Threls\ThrelsInvoicingModule\Models;

use Illuminate\Database\Eloquent\Model;
use Threls\ThrelsInvoicingModule\InvoicingModelResolverManager;

class TransactionPayment extends Model
{
    protected $table = 'transaction_payments';

    protected $guarded = ['id'];

    public function transaction()
    {
        return $this->belongsTo(InvoicingModelResolverManager::getModelClass('transaction'));
    }

    public function paymentable()
    {
        return $this->morphTo();
    }
}
