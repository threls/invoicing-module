<?php

namespace Threls\ThrelsInvoicingModule\Actions;

use Threls\ThrelsInvoicingModule\Dto\CreateVatRateDto;
use Threls\ThrelsInvoicingModule\Models\VatRate;

class CreateVatRateAction
{
    public function execute(CreateVatRateDto $createVatRateDto): VatRate
    {
       return VatRate::updateOrCreate([
            'rate' => $createVatRateDto->rate,
        ]);

    }

}
