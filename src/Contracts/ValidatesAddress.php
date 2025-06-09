<?php

namespace ViicSlen\Addressable\Contracts;

use ViicSlen\Addressable\Models\AddressBook\Address;

interface ValidatesAddress
{
    public function __invoke(Address $address): bool;
}
