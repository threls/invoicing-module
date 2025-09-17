<?php

namespace Threls\ThrelsInvoicingModule;

use Illuminate\Database\Eloquent\Relations\Relation;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\ModelStatus\ModelStatusServiceProvider;
use Threls\ThrelsInvoicingModule\Commands\ThrelsInvoicingModuleCommand;

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
            ->hasMigrations([
                'create_vat_rates_table',
                'create_transactions_table',
                'create_transaction_items_table',
                'create_invoices_table',
                'create_credit_notes_table',
                'create_transaction_payments_table',
                'add_vat_amount_on_credit_notes_table'
            ])
            ->hasCommand(ThrelsInvoicingModuleCommand::class)
            ->publishesServiceProvider(ModelStatusServiceProvider::class);

    }

    public function packageRegistered()
    {
        InvoicingModelResolverManager::resolveModels();
    }

    public function packageBooted()
    {
        Relation::morphMap(config('invoicing-module.models', InvoicingModelResolverManager::$defaultModels));
    }
}
