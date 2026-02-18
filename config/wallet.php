<?php

return [
    'default_currency' => env('WALLET_CURRENCY', 'INR'),
    'supported_types' => ['payin', 'payout'],
    'limits' => [
        'payin_credit_max' => env('PAYIN_CREDIT_MAX', 500000),
        'payout_debit_max' => env('PAYOUT_DEBIT_MAX', 500000),
    ],
];
