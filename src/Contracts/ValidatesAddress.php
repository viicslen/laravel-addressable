<?php

namespace ViicSlen\Addressable\Contracts;

use ViicSlen\Addressable\Models\Address;

interface ValidatesAddress
{
    /**
     * @return bool|null Null when the address could not be checked (unsupported, incomplete, or the call failed),
     *                   so it stays unvalidated and can be retried.
     */
    public function __invoke(Address $address): ?bool;
}
