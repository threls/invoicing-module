<?php

namespace Threls\ThrelsInvoicingModule\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Threls\ThrelsInvoicingModule\Models\Invoice;

class InvoiceCreatedEvent implements ShouldDispatchAfterCommit
{
    use Dispatchable;

    public function __construct(public readonly Invoice $invoice) {}
}
