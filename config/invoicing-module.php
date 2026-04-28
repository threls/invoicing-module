<?php

use Threls\ThrelsInvoicingModule\Models\CreditNote;
use Threls\ThrelsInvoicingModule\Models\Invoice;
use Threls\ThrelsInvoicingModule\Models\PDFInvoice;
use Threls\ThrelsInvoicingModule\Models\Transaction;
use Threls\ThrelsInvoicingModule\Models\TransactionItem;
use Threls\ThrelsInvoicingModule\Models\TransactionPayment;

return [

    'date' => [
        'format' => 'Y-m-d',
    ],
    'logo' => '',

    'currency' => [
        'code' => 'EUR',

        'symbol' => '€',

        /*
         * ex. 19.00
         */
        'decimals' => 2,

        /*
         * ex. 1.99
         */
        'decimal_point' => '.',

        /*
         * ex. 1,999.00
         */
        'thousands_separator' => '',

        /*
         * Supported tags {VALUE}, {SYMBOL}, {CODE}
         * ex. 1.99 €,  € 1.99
         */
        'format' => '{SYMBOL} {VALUE}',
    ],

    'template' => 'template-1',

    'seller' => [

        'attributes' => [
            'name' => 'Threls',
            'address' => '89982 Pfeffer Falls Damianstad, CO 66972-8160',
            'email' => 'test@gmail.com',
            'vat_nr' => '123456789',
            'exo_nr' => '12345678444',
            'phone' => '760-355-3930',
            'custom_fields' => [
                // 'SWIFT' => 'BANK101',
            ],
        ],
    ],

    'models' => [
        'creditNote' => CreditNote::class,
        'invoice' => Invoice::class,
        'pdfInvoice' => PDFInvoice::class,
        'transaction' => Transaction::class,
        'transactionItem' => TransactionItem::class,
        'transactionPayment' => TransactionPayment::class,
    ],
];
