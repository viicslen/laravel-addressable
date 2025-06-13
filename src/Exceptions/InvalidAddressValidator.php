<?php

namespace ViicSlen\Addressable\Exceptions;

use Exception;
use ViicSlen\Addressable\Contracts\ValidatesAddress;

class InvalidAddressValidator extends Exception
{
    public function __construct(?string $validator)
    {
        parent::__construct(
            message: $validator === null
                ? 'An address validator must be configured in the `addressable.default_validator` config in order to validate addresses.'
                : sprintf("The configured address validator `%s` is not valid. It must implement `%s`.", $validator, ValidatesAddress::class)
        );
    }
}
