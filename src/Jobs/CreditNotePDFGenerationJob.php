<?php

namespace Threls\ThrelsInvoicingModule\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use Threls\ThrelsInvoicingModule\Dto\CreditNotePDFGenerationDto;
use Threls\ThrelsInvoicingModule\Models\CreditNote;
use Threls\ThrelsInvoicingModule\Models\PDFInvoice;
use Threls\ThrelsInvoicingModule\Models\TransactionItem;

class CreditNotePDFGenerationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected CreditNote $creditNote;

    protected CreditNotePDFGenerationDto $creditNotePDFGenerationDto;

    protected Party $seller;

    protected Party $customer;

    protected array $invoiceItems;

    protected PDFInvoice $invoicePDF;

    public function __construct(CreditNote $creditNote, CreditNotePDFGenerationDto $creditNotePDFGenerationDto)
    {
        $this->creditNote = $creditNote;
        $this->creditNotePDFGenerationDto = $creditNotePDFGenerationDto;

    }

    public function handle(): void
    {
        $this
            ->setSeller()
            ->setCustomer()
            ->setItems()
            ->createInvoice()
            ->addMediaToDisk();

    }

    protected function setSeller()
    {
        $this->seller = new Party([
            'name' => config('invoicing-module.seller.attributes.name'),
            'address' => config('invoicing-module.seller.attributes.address'),
            'email' => config('invoicing-module.seller.attributes.email'),
            'phone' => config('invoicing-module.seller.attributes.phone'),
            'vat_nr' => config('invoicing-module.seller.attributes.vat_nr'),
            'exo_nr' => config('invoicing-module.seller.attributes.exo_nr'),
        ]);

        return $this;

    }

    protected function setCustomer()
    {
        $this->customer = new Party([
            'name' => $this->creditNotePDFGenerationDto->customerName ?? 'Cash Sale',
            'address' => $this->creditNotePDFGenerationDto->customerAddress,
            'email' => $this->creditNotePDFGenerationDto->customerEmail,
            'phone' => $this->creditNotePDFGenerationDto->customerPhone,
        ]);

        return $this;
    }

    protected function setItems()
    {
        /** @var Collection<TransactionItem> $items */
        $items = $this->creditNote->invoice->transaction->transactionItems;

        $invoiceItems = [];
        $items->each(function (TransactionItem $transactionItem) use (&$invoiceItems) {

            $priceWithoutVat = $transactionItem->total_amount->minus($transactionItem->vat_amount);

            $invoiceItems[] = InvoiceItem::make($transactionItem->description)
                ->pricePerUnit($priceWithoutVat->getMinorAmount()->toFloat() / 100)
                ->quantity($transactionItem->qty)
                ->tax($transactionItem->vat_amount->getMinorAmount()->toFloat() / 100);
        });

        $this->invoiceItems = $invoiceItems;

        return $this;
    }

    protected function createInvoice()
    {
        $invoicePDF = PDFInvoice::make($this->invoicePDFGenerationData->name ?? 'Credit Note')
            ->series(config('invoicing-module.serial_number.series'))
            ->sequence($this->creditNote->id)
            ->sequencePadding(config('invoicing-module.serial_number.sequence_padding'))
            ->delimiter(config('invoicing-module.serial_number.delimiter'))
            ->serialNumberFormat(config('invoicing-module.serial_number.format'))
            ->seller($this->seller)
            ->buyer($this->customer)
            ->date(Carbon::parse($this->creditNote->created_at))
            ->dateFormat(config('invoicing-module.date.format'))
            ->addItems($this->invoiceItems)
            ->currencySymbol(config('invoicing-module.currency.symbol'))
            ->currencyCode(config('invoicing-module.currency.code'))
            ->currencyDecimals(config('invoicing-module.currency.decimals'))
            ->currencyDecimalPoint(config('invoicing-module.currency.decimal_point'))
            ->currencyFormat(config('invoicing-module.currency.format'))
            ->currencyThousandsSeparator(config('invoicing-module.currency.thousands_separator'))
            ->totalAmount($this->creditNote->total_amount->getMinorAmount()->toFloat() / 100)
            ->template('template-1');

        $this->invoicePDF = $invoicePDF;

        return $this;
    }

    protected function addMediaToDisk(): void
    {
        $this->creditNote->addMediaFromStream($this->invoicePDF->stream())->usingFileName($this->invoicePDF->filename)->toMediaCollection(CreditNote::MEDIA_CREDIT_NOTE);

    }
}
