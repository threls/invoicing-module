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
use LaravelDaily\Invoices\Invoice as PDFInvoice;
use Threls\ThrelsInvoicingModule\Dto\InvoicePDFGenerationDto;
use Threls\ThrelsInvoicingModule\Models\Invoice;
use Threls\ThrelsInvoicingModule\Models\TransactionItem;

class InvoicePDFGenerationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Invoice $invoice;

    protected InvoicePDFGenerationDto $invoicePDFGenerationData;

    protected Party $seller;

    protected Party $customer;

    protected array $invoiceItems;

    public function __construct(Invoice $invoice, InvoicePDFGenerationDto $invoicePDFGenerationData)
    {
        $this->invoice = $invoice;
        $this->invoicePDFGenerationData = $invoicePDFGenerationData;

    }

    public function handle(): void
    {
        $this
            ->setSeller()
            ->setCustomer()
            ->setItems()
            ->createInvoice();

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
            'name' => $this->invoicePDFGenerationData->customerName ?? 'Cash Sale',
            'address' => $this->invoicePDFGenerationData->customerAddress,
            'email' => $this->invoicePDFGenerationData->customerEmail,
            'phone' => $this->invoicePDFGenerationData->customerPhone,
        ]);

        return $this;
    }

    protected function setItems()
    {
        /** @var Collection<TransactionItem> $items */
        $items = $this->invoice->transaction->transactionItems;

        $invoiceItems = [];
        $items->each(function (TransactionItem $transactionItem) use (&$invoiceItems) {
            $invoiceItems[] = InvoiceItem::make($transactionItem->description)
                ->pricePerUnit($transactionItem->amount->getMinorAmount()->toFloat() / 100)
                ->quantity($transactionItem->qty)
                ->tax($transactionItem->vat_amount->getMinorAmount()->toFloat() / 100);
        });

        $this->invoiceItems = $invoiceItems;

        return $this;
    }

    protected function createInvoice()
    {
        $invoice = PDFInvoice::make($this->invoicePDFGenerationData->name ?? 'Invoice')
            ->series('')
            ->sequence($this->invoice->id)
            ->serialNumberFormat(config('invoicing-module.serial_number.format'))
            ->seller($this->seller)
            ->buyer($this->customer)
            ->date(Carbon::parse($this->invoice->created_at))
            ->dateFormat(config('invoicing-module.date.format'))
            ->addItems($this->invoiceItems)
            ->currencySymbol(config('invoicing-module.currency.symbol'))
            ->currencyCode(config('invoicing-module.currency.code'))
            ->currencyFormat(config('invoicing-module.currency.format'))
            ->currencyThousandsSeparator(config('invoicing-module.currency.thousands_separator'))
            ->currencyDecimalPoint(config('invoicing-module.currency.decimal_point'))
            ->logo(config('invoicing-module.logo'))
            ->save(config('invoicing-module.disk'));
        // ->template()

        return $invoice;

    }
}
