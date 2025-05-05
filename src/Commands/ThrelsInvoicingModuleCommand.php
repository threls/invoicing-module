<?php

namespace Threls\ThrelsInvoicingModule\Commands;

use Illuminate\Console\Command;
use Threls\ThrelsInvoicingModule\Dto\CreateCreditNoteDto;
use Threls\ThrelsInvoicingModule\Dto\CreateInvoiceDto;
use Threls\ThrelsInvoicingModule\Dto\CreateTransactionDto;
use Threls\ThrelsInvoicingModule\Dto\CreateTransactionItemDto;
use Threls\ThrelsInvoicingModule\Dto\CreditNotePDFGenerationDto;
use Threls\ThrelsInvoicingModule\Dto\InvoicePDFGenerationDto;
use Threls\ThrelsInvoicingModule\Enums\CreditNoteStatusEnum;
use Threls\ThrelsInvoicingModule\Enums\TransactionStatusEnum;
use Threls\ThrelsInvoicingModule\Enums\TransactionTypeEnum;
use Threls\ThrelsInvoicingModule\Facades\ThrelsInvoicingModule;
use Threls\ThrelsInvoicingModule\Models\CreditNote;
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
                description: 'Adult Ticket',
                qty: 1,
                amount: 2200,
                totalAmount: 2200,
                currency: 'EUR',
                vatId: 1
            ),
            new CreateTransactionItemDto(
                modelType: Invoice::class,
                modelId: 2,
                description: 'Child 3-14 Ticket',
                qty: 1,
                amount: 1500,
                totalAmount: 1500,
                currency: 'EUR',
                vatId: 1
            ),
        ];

        $dto = new CreateTransactionDto(
            userId: 1,
            type: TransactionTypeEnum::PURCHASE,
            status: TransactionStatusEnum::PAID,
            amount: 3700,
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
            name: 'Invoice',
            customerName: 'Sabina',
            customerAddress: 'Threls Ltd, Gozitano Village, Mgarr Road Xewkija',
            customerPhone: '+355692222332',
            customerEmail: 'sabina@threls.com',
        );

        ThrelsInvoicingModule::generateInvoicePdf($invoice, $pdfDto);

        //        /* credit note */
        //
        //        $creditNoteDto = new CreateCreditNoteDto(
        //            invoiceId: $invoice->id,
        //            amount: $invoice->total_amount->getMinorAmount()->toInt(),
        //            currency: $invoice->currency,
        //            reason: 'Refund requested',
        //            status: CreditNoteStatusEnum::SUCCEEDED,
        //        );
        //
        //        $creditNote = ThrelsInvoicingModule::createCreditNote($creditNoteDto);
        //
        //        $item = new CreateTransactionItemDto(
        //            modelType: CreditNote::class,
        //            modelId: $creditNote->id,
        //            description: 'Test credit note item',
        //            qty: 1,
        //            amount: $creditNote->amount->getMinorAmount()->toInt(),
        //            totalAmount: $creditNote->amount->getMinorAmount()->toInt(),
        //            currency: 'EUR',
        //            vatId: 1
        //        );
        //
        //        $creditNoteTransactionDto = new CreateTransactionDto(
        //            userId: 1,
        //            type: TransactionTypeEnum::CREDIT_NOTE,
        //            status: TransactionStatusEnum::PAID,
        //            amount: 1000,
        //            currency: 'EUR',
        //            items: collect($item),
        //        );
        //
        //        ThrelsInvoicingModule::createTransaction($creditNoteTransactionDto);
        //
        //        $creditNotePdfDto = new CreditNotePDFGenerationDto(
        //            name: 'CreditNote 1',
        //            customerName: 'Customer 1',
        //            customerAddress: 'Customer Address 1',
        //            customerPhone: '+355692222332',
        //            customerEmail: 'sabina@threls.com',
        //        );
        //
        //        ThrelsInvoicingModule::generateCreditNotePdf($creditNote, $creditNotePdfDto);

        $this->comment('All done');

        return self::SUCCESS;

    }
}
