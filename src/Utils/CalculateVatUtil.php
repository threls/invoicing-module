<?php

namespace Threls\ThrelsInvoicingModule\Utils;

class CalculateVatUtil
{
    public function extractVAT(int $totalPrice, int $vatRate): int
    {
        if ($totalPrice == 0) {
            return 0;
        }

        return (int) round($totalPrice * $vatRate / (100 + $vatRate));
    }
}
