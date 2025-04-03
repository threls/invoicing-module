<?php

return [

    'date' => [
        'format' => 'Y-m-d',
    ],
    'logo' => '',

    'serial_number' => [
        'series' => 'AA',
        'sequence' => 1,

        /*
         *  ex. 00001
         */
        'sequence_padding' => 5,
        'delimiter' => '-',

        /*
         * ex. AA-00001
         */
        'format' => '{SERIES}{DELIMITER}{SEQUENCE}',
    ],

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
         * ex. 1.99 €
         */
        'format' => '{VALUE} {SYMBOL}',
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
