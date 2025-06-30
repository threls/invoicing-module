<?php

namespace Threls\ThrelsInvoicingModule;

use Threls\ThrelsInvoicingModule\Models\CreditNote;
use Threls\ThrelsInvoicingModule\Models\Invoice;
use Threls\ThrelsInvoicingModule\Models\PDFInvoice;
use Threls\ThrelsInvoicingModule\Models\Transaction;
use Threls\ThrelsInvoicingModule\Models\TransactionItem;
use Threls\ThrelsInvoicingModule\Models\TransactionPayment;

class InvoicingModelResolverManager
{
    public static array $models = [];

    public static array $defaultModels = [
        'creditNote' => CreditNote::class,
        'invoice' => Invoice::class,
        'pdfInvoice' => PDFInvoice::class,
        'transaction' => Transaction::class,
        'transactionItem' => TransactionItem::class,
        'transactionPayment' => TransactionPayment::class,
    ];

    public static function resolveModels(): void
    {
        static::$models = config('invoicing-module.models', static::$defaultModels);
    }

    public static function useModel(string $name, string $class): void
    {
        static::$models[$name] = $class;
    }

    public static function getModelClass(string $name): string
    {
        return static::$models[$name] ?? throw new \InvalidArgumentException("Model [$name] is not registered.");
    }

    public static function newModel(string $name)
    {
        $modelClass = static::getModelClass($name);

        return new $modelClass;
    }
}
