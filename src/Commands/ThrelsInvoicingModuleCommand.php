<?php

namespace Threls\ThrelsInvoicingModule\Commands;

use Illuminate\Console\Command;

class ThrelsInvoicingModuleCommand extends Command
{
    public $signature = 'invoicing-module';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
