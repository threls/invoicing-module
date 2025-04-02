<?php

namespace Threls\ThrelsInvoicingModule;

use Threls\ThrelsInvoicingModule\Actions\CreateCreditNoteAction;
use Threls\ThrelsInvoicingModule\Actions\CreateInvoiceAction;
use Threls\ThrelsInvoicingModule\Actions\CreateTransactionAction;
use Threls\ThrelsInvoicingModule\Actions\CreateVatRateAction;
use Threls\ThrelsInvoicingModule\Actions\GenerateInvoicePDFAction;
use Threls\ThrelsInvoicingModule\Actions\LinkTransactionWithPaymentAction;
use Threls\ThrelsInvoicingModule\Actions\UpdateCreditNoteStatusAction;
use Threls\ThrelsInvoicingModule\Actions\UpdateTransactionStatusAction;
use Threls\ThrelsInvoicingModule\Dto\CreateCreditNoteDto;
use Threls\ThrelsInvoicingModule\Dto\CreateInvoiceDto;
use Threls\ThrelsInvoicingModule\Dto\CreateTransactionDto;
use Threls\ThrelsInvoicingModule\Dto\CreateVatRateDto;
use Threls\ThrelsInvoicingModule\Dto\InvoicePDFGenerationDto;
use Threls\ThrelsInvoicingModule\Dto\LinkTransactionWithPaymentDto;
use Threls\ThrelsInvoicingModule\Dto\UpdateCreditNoteStatusDto;
use Threls\ThrelsInvoicingModule\Dto\UpdateTransactionStatusDto;
use Threls\ThrelsInvoicingModule\Models\CreditNote;
use Threls\ThrelsInvoicingModule\Models\Invoice;
use Threls\ThrelsInvoicingModule\Models\Transaction;
use Threls\ThrelsInvoicingModule\Models\VatRate;

class ThrelsInvoicingModule {

    public function createTransaction(CreateTransactionDto $createTransactionDto): Transaction
    {
        return app(CreateTransactionAction::class)->execute($createTransactionDto);
    }

    public function linkTransactionWithPayment(LinkTransactionWithPaymentDto $updateTransactionPaymentDto): void
    {
        app(LinkTransactionWithPaymentAction::class)->execute($updateTransactionPaymentDto);
    }

    public function updateTransactionStatus(UpdateTransactionStatusDto $updateTransactionStatusDto): void
    {
        app(UpdateTransactionStatusAction::class)->execute($updateTransactionStatusDto);
    }

    public function createInvoice(Transaction $transaction, CreateInvoiceDto $createInvoiceDto): Invoice
    {
        return app(CreateInvoiceAction::class)->execute($transaction, $createInvoiceDto);

    }

    public function createCreditNote(CreateCreditNoteDto $createCreditNoteDto): CreditNote
    {
        return app(CreateCreditNoteAction::class)->execute($createCreditNoteDto);
    }

    public function updateCreditNoteStatus(UpdateCreditNoteStatusDto $updateCreditNoteStatusDto): void
    {
        app(UpdateCreditNoteStatusAction::class)->execute($updateCreditNoteStatusDto);
    }

    public function createVatRate(CreateVatRateDto $createVatRateDto): VatRate
    {
        return app(CreateVatRateAction::class)->execute($createVatRateDto);
    }

    public function generateInvoicePdf(Invoice $invoice, InvoicePDFGenerationDto $invoicePdfGenerationDto): void
    {
        app(GenerateInvoicePdfAction::class)->execute($invoice,$invoicePdfGenerationDto);

    }

}
