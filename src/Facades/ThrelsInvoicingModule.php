<?php

namespace Threls\ThrelsInvoicingModule\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Threls\ThrelsInvoicingModule\ThrelsInvoicingModule
 */
class ThrelsInvoicingModule extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Threls\ThrelsInvoicingModule\ThrelsInvoicingModule::class;
    }
}
