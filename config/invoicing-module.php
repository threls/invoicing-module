<?php

return [

    'date' => [
        'format' => 'Y-m-d',
    ],
    'logo' => '',

    'serial_number' => [
        'series'   => 'AA',
        'sequence' => 1,

        /*
         * Sequence will be padded accordingly, for ex. 00001
         */
        'sequence_padding' => 5,
        'delimiter'        => '-',

        /*
         * Supported tags {SERIES}, {DELIMITER}, {SEQUENCE}
         * Example: AA-00001
         */
        'format' => '{SERIES}{DELIMITER}{SEQUENCE}',
    ],

    'currency' => [
        'code' => 'EUR',

        /*
         * Usually cents
         * Used when spelling out the amount and if your currency has decimals.
         *
         * Example: Amount in words: Eight hundred fifty thousand sixty-eight EUR and fifteen ct.
         */
        'fraction' => 'ct.',
        'symbol'   => '€',

        /*
         * Example: 19.00
         */
        'decimals' => 2,

        /*
         * Example: 1.99
         */
        'decimal_point' => '.',

        /*
         * By default empty.
         * Example: 1,999.00
         */
        'thousands_separator' => '',

        /*
         * Supported tags {VALUE}, {SYMBOL}, {CODE}
         * Example: 1.99 €
         */
        'format' => '{VALUE} {SYMBOL}',
    ],

    'paper' => [
        'size'        => 'a4',
        'orientation' => 'portrait',
    ],

    'disk' => 'local',

    'seller' => [

        'attributes' => [
            'name'          => 'Threls',
            'address'       => '89982 Pfeffer Falls Damianstad, CO 66972-8160',
            'email'       => 'test@gmail.com',
            'vat_nr'           => '123456789',
            'exo_nr'           => '12345678444',
            'phone'         => '760-355-3930',
            'custom_fields' => [
                'SWIFT' => 'BANK101',
            ],
        ],
    ],
];
