<?php

namespace Threls\ThrelsInvoicingModule;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\ModelStatus\ModelStatusServiceProvider;
use Threls\ThrelsInvoicingModule\Commands\ThrelsInvoicingModuleCommand;
use Threls\ThrelsInvoicingModule\Services\InvoicingSystemService;

class ThrelsInvoicingModuleServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('invoicing-module')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_invoicing_module_table')
            ->hasCommand(ThrelsInvoicingModuleCommand::class)
            ->publishesServiceProvider(ModelStatusServiceProvider::class);

        $this->app->singleton(InvoicingSystemService::class, function ($app) {
            return new InvoicingSystemService;
        });
    }
}
