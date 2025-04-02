<?php

namespace Threls\ThrelsInvoicingModule\Actions;

use Threls\ThrelsInvoicingModule\Dto\InvoicePDFGenerationDto;
use Threls\ThrelsInvoicingModule\Jobs\InvoicePDFGenerationJob;
use Threls\ThrelsInvoicingModule\Models\Invoice;

class GenerateInvoicePDFAction
{
    public function execute(Invoice $invoice, InvoicePDFGenerationDto $invoicePDFGenerationData)
    {
        InvoicePDFGenerationJob::dispatch($invoice, $invoicePDFGenerationData);

    }
}
