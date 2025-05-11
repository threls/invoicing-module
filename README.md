# Reusable invoicing system

[![Latest Version on Packagist](https://img.shields.io/packagist/v/threls/invoicing-module.svg?style=flat-square)](https://packagist.org/packages/threls/invoicing-module)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/threls/invoicing-module/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/threls/invoicing-module/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/threls/invoicing-module/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/threls/invoicing-module/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/threls/invoicing-module.svg?style=flat-square)](https://packagist.org/packages/threls/invoicing-module)

Threls invoicing system library following EXO compliance requirements


## Installation

You can install the package via composer:

```bash
composer require threls/invoicing-module
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="invoicing-module-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="invoicing-module-config"
```

This is the contents of the published config file:

```php
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
];
```

You can publish the views using

```bash
php artisan vendor:publish --tag="invoicing-module-views"
```
On the published views folder you will find the pdf template. 
If you want to add another format you can create a new template 
and change the config entry 'template' to your custom template name.

##  🔹Requirements

- Having that vat rates are saved on database, you will first need
to create your vat rates (having a seeder) that you will 
use for transactions. You can use `createVatRate` facade method 
explained below.

## 🔹 Available Methods

This package publishes a laravel Facade named `ThrelsInvoicingModule`.
After migration tables are created and all is setup, you can use this 
facade based on your needs on the app you are creating.


```php

ThrelsInvoicingModule::createTransaction(CreateTransactionDto $dto);
ThrelsInvoicingModule::createVatRate(CreateVatRateDto $dto);
ThrelsInvoicingModule::linkTransactionWithPayment(LinkTransactionWithPaymentDto $dto);
ThrelsInvoicingModule::updateTransactionStatus(UpdateTransactionStatusDto $dto);
ThrelsInvoicingModule::createInvoice(Transaction $transaction, CreateInvoiceDto $dto);
ThrelsInvoicingModule::generateInvoicePdf(Invoice $invoice, InvoicePDFGenerationDto $dto);
ThrelsInvoicingModule::createCreditNote(CreateCreditNoteDto $dto);
ThrelsInvoicingModule::updateCreditNoteStatus(UpdateCreditNoteStatusDto $dto);
ThrelsInvoicingModule::generateCreditNotePdf(CreditNote $creditNnote, InvoicePDFGenerationDto $dto);

```


## 🔹 Basic Usage

### ➕ Create a new transaction.

```php

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
        ];

$transaction = ThrelsInvoicingModule::createTransaction(new CreateTransactionDto(
      userId: 1,
      type: TransactionTypeEnum::PURCHASE,
      status: TransactionStatusEnum::PENDING,
      amount: 1000,
      currency: 'EUR',
      items: collect($items),
));

```
<br>

### ➕ Create a new vat rate.


```php

$vatRate = ThrelsInvoicingModule::createVatRate(new CreateVatRateDto(
      userId: 20,
));
```

### ➕ Link transaction with a payment 
(can be any type implemented in the app ex.Stripe, Apple, Google).

```php

ThrelsInvoicingModule::linkTransactionWithPayment(new LinkTransactionWithPaymentDto(
      transactionId: 1,
      paymentableId: 1,
      paymentableType: StripePaymentIntent::Class
   
));
```

### ➕ Update transaction status

Available transaction statuses  : `SCHEDULED`,`PENDING`,
`ON_HOLD`,`PAID`,`CANCELLED`,`FAILED`,`SUPERDEDED`

```php

ThrelsInvoicingModule::updateTransactionStatus(new UpdateTransactionStatusDto(
      transactionId: 1,
      status: TransactionStatusEnum::PAID,
      reason: null
));
```

### ➕ Create an invoice for a paid transaction.

```php

ThrelsInvoicingModule::createInvoice($transaction, new CreateInvoiceDto(
      vatAmount: 100,
      totalAmount: 5000,
      currency: 'EUR'
));
```

### ➕ Generate a pdf for the invoice

Generates a pdf media for the invoices, it will be stored on the disk specified on config file.
This lib uses spatie/media package and saves all media files on database.

```php

ThrelsInvoicingModule::generateInvoicePdf($invoice, new InvoicePDFGenerationDto(
      name: 'Invoice name', //nullable, default 'Invoice'
      customerName: 'Customer test',
      customerAddress: 'test address',
      customerPhone: '+355692223232',
      customerEmail: 'test@threls.com'
));
```

### ➕ Create a credit note for a refunded transaction.

Available credit note statuses : `PENDING`,
`SUCCEEDED`,`CANCELLED`,`FAILED`,`REQUIRES_ACTION`

```php

ThrelsInvoicingModule::createCreditNote(new CreateCreditNoteDto(
      invoiceId: 1,
      amount: 5000,
      currency: 'EUR',
      reason: 'Client requested refund',
      status: CreditNoteStatusEnum::SUCCEEDED,
      statusReason: null
));
```

### ➕ Update credit note status

```php

ThrelsInvoicingModule::updateCreditNoteStatus(new UpdateCreditNoteStatusDto(
      creditNoteId: 1,
      status: CreditNoteStatusEnum::FAILED,
      reason: 'Payment failed'
));
```

### ➕ Generate a pdf for the credit note

Generates a pdf media for the credit notes, it will be stored on the disk specified on config file.
This lib uses spatie/media package and saves all media files on database.

```php

ThrelsInvoicingModule::generateCreditNotePdf($creditNote, new CreditNotePDFGenerationDto(
      name: 'Credit note name', //nullable, default 'Credit note'
      customerName: 'Customer test',
      customerAddress: 'test address',
      customerPhone: '+355692223232',
      customerEmail: 'test@threls.com'
));
```

## Traits
- Use IsTransactionable trait for every model that can be a 
transaction item

```php
    use IsTransactionable;
```

## Casts

```php

 'amount' => MoneyCast::class,

```

## Test

You can use a command included in library to test all 
the library functionalities using test data.

```
php artisan invoicing-module
```

## Blade templates

Blade templates are under /templates dir.
You can add your templates and specify in config file as mentioned.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Sabina Haloci](https://github.com/sabina)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
