<?php

namespace Threls\ThrelsInvoicingModule\Actions;

use Threls\ThrelsInvoicingModule\Dto\InvoicePDFGenerationDto;
use Threls\ThrelsInvoicingModule\Enums\GeneratedPdfStatusEnum;
use Threls\ThrelsInvoicingModule\Jobs\InvoicePDFGenerationJob;
use Threls\ThrelsInvoicingModule\Models\GeneratedPdf;
use Threls\ThrelsInvoicingModule\Models\Invoice;

class GenerateInvoicePDFAction
{
    public function execute(Invoice $invoice, InvoicePDFGenerationDto $invoicePDFGenerationData): void
    {
        $generatedPdf = GeneratedPdf::create([
            'model_type' => Invoice::class,
            'model_id' => $invoice->id,
            'status' => GeneratedPdfStatusEnum::PENDING,
        ]);

        InvoicePDFGenerationJob::dispatch($invoice, $invoicePDFGenerationData, $generatedPdf);
    }
}
