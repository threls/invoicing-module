<?php

namespace Threls\ThrelsInvoicingModule\Commands;

use Illuminate\Console\Command;
use Threls\ThrelsInvoicingModule\Dto\CreateInvoiceDto;
use Threls\ThrelsInvoicingModule\Dto\CreateTransactionDto;
use Threls\ThrelsInvoicingModule\Dto\CreateTransactionItemDto;
use Threls\ThrelsInvoicingModule\Dto\InvoicePDFGenerationDto;
use Threls\ThrelsInvoicingModule\Enums\TransactionStatusEnum;
use Threls\ThrelsInvoicingModule\Enums\TransactionTypeEnum;
use Threls\ThrelsInvoicingModule\Facades\ThrelsInvoicingModule;
use Threls\ThrelsInvoicingModule\Models\Invoice;

class ThrelsInvoicingModuleCommand extends Command
{
    public $signature = 'invoicing-module';

    public $description = 'Test transaction';

    public function handle(): int
    {
        $items = [
            new CreateTransactionItemDto(
                modelType: Invoice::class,
                modelId: 1,
                description: 'Test transaction item 1',
                qty: 1,
                amount: 100,
                totalAmount: 120,
                currency: 'EUR',
                vatId: 1
            ),
            new CreateTransactionItemDto(
                modelType: Invoice::class,
                modelId: 2,
                description: 'Test transaction item 2',
                qty: 1,
                amount: 200,
                totalAmount: 240,
                currency: 'EUR',
                vatId: 1
            ),
        ];

        $dto = new CreateTransactionDto(
            userId: 1,
            type: TransactionTypeEnum::PURCHASE,
            status: TransactionStatusEnum::PENDING,
            amount: 1000,
            currency: 'EUR',
            items: collect($items),
        );

        $transaction = ThrelsInvoicingModule::createTransaction($dto);

        $invoiceDto = new CreateInvoiceDto(
            vatAmount: $transaction->vat_amount->getMinorAmount()->toInt(),
            totalAmount: $transaction->amount->getMinorAmount()->toInt(),
            currency: $transaction->currency,
        );

        $invoice = ThrelsInvoicingModule::createInvoice($transaction, $invoiceDto);

        $pdfDto = new InvoicePDFGenerationDto(
            name: 'Invoice 1',
            customerName: 'Customer 1',
            customerAddress: 'Customer Address 1',
            customerPhone: '+355692222332',
            customerEmail: 'sabina@threls.com',
        );

        ThrelsInvoicingModule::generateInvoicePdf($invoice, $pdfDto);

        $this->comment('All done');

        return self::SUCCESS;

    }
}
