<?php

namespace ViicSlen\Addressable\Exceptions\AddressBook;

use Exception;

class InvalidAddressValidator extends Exception
{
    public function __construct(?string $validator)
    {
        parent::__construct(
            message: $validator === null
                ? 'An address validator must be configured in the `addressable.default_validator` config in order to validate addresses.'
                : "The configured address validator `{$validator}` is not valid. It must implement `ViicSlen\Addressable\Contracts\ValidatesAddress`."
        );
    }
}
