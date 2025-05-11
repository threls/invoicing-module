<?php

namespace Threls\ThrelsInvoicingModule\Utils;

class CalculateVatUtil
{
    public function extractVAT(int $totalPrice, int $vatRate): int
    {
        return (int) round($totalPrice * $vatRate / (100 + $vatRate), 0, PHP_ROUND_HALF_UP);
    }
}
