<?php

namespace Threls\ThrelsInvoicingModule\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Threls\ThrelsInvoicingModule\Models\Invoice;

class InvoiceCreatedEvent
{
    use Dispatchable;

    public function __construct(public readonly Invoice $invoice) {}
}
