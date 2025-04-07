<?php

namespace Threls\ThrelsInvoicingModule\Actions;

use Threls\ThrelsInvoicingModule\Dto\CreditNotePDFGenerationDto;
use Threls\ThrelsInvoicingModule\Jobs\CreditNotePDFGenerationJob;
use Threls\ThrelsInvoicingModule\Models\CreditNote;

class GenerateCreditNotePDFAction
{
    public function execute(CreditNote $creditNote, CreditNotePDFGenerationDto $creditNotePDFGenerationDto)
    {
        CreditNotePDFGenerationJob::dispatch($creditNote, $creditNotePDFGenerationDto);

    }
}
