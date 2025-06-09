<?php

return [
    'model' => [
        'connection' => env('ADDRESSABLE_MODEL_CONNECTION'),
        'table' => env('ADDRESSABLE_MODEL_TABLE', 'addresses'),
        'class' => env('ADDRESSABLE_MODEL_CLASS', \ViicSlen\Addressable\Models\AddressBook\Address::class),
    ],

    'validation' => [
        'enabled' => env('ADDRESSABLE_VALIDATION_ENABLED', true),
        'queued' => env('ADDRESSABLE_VALIDATION_QUEUED', true),
        'validator' => env('ADDRESSABLE_VALIDATION_DEFAULT', UPSValidator::class),
    ],
];
