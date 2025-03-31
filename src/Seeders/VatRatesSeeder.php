<?php

namespace Threls\ThrelsInvoicingModule\Seeders;

use Illuminate\Database\Seeder;
use Threls\ThrelsInvoicingModule\Models\VatRate;

class VatRatesSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VatRate::updateOrCreate(
            ['rate' => '20'],
        );
        VatRate::updateOrCreate(
            ['rate' => '18'],
        );
        VatRate::updateOrCreate(
            ['rate' => '0'],
        );
    }
}
