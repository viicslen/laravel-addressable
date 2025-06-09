<?php

namespace ViicSlen\Addressable\Exceptions\AddressBook;

use Exception;

class ImmutableAddressException extends Exception
{
    public function __construct(string $message = 'This address is immutable and cannot be updated.', int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
