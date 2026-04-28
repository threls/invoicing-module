<?php

namespace Threls\ThrelsInvoicingModule\Actions;

use Threls\ThrelsInvoicingModule\Dto\CreditNotePDFGenerationDto;
use Threls\ThrelsInvoicingModule\Enums\GeneratedPdfStatusEnum;
use Threls\ThrelsInvoicingModule\Jobs\CreditNotePDFGenerationJob;
use Threls\ThrelsInvoicingModule\Models\CreditNote;
use Threls\ThrelsInvoicingModule\Models\GeneratedPdf;

class GenerateCreditNotePDFAction
{
    public function execute(CreditNote $creditNote, CreditNotePDFGenerationDto $creditNotePDFGenerationDto): void
    {
        $generatedPdf = GeneratedPdf::create([
            'model_type' => CreditNote::class,
            'model_id' => $creditNote->id,
            'status' => GeneratedPdfStatusEnum::PENDING,
        ]);

        CreditNotePDFGenerationJob::dispatch($creditNote, $creditNotePDFGenerationDto, $generatedPdf);
    }
}
