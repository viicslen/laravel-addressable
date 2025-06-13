<?php

namespace ViicSlen\Addressable;

use ViicSlen\Addressable\Contracts\ValidatesAddress;
use ViicSlen\Addressable\Exceptions\InvalidAddressValidator;
use ViicSlen\Addressable\Models\Address;

class Addressable
{
    public function shouldValidateAddress(Address $address): bool
    {
        return $address->wasChanged([
            'street1',
            'street2',
            'city',
            'state',
            'country_code',
            'postal_code',
        ]);
    }

    public function validateAddress(Address $address, ?ValidatesAddress $validator = null): bool
    {
        if ($validator === null) {
            /** @var \ViicSlen\Addressable\Contracts\ValidatesAddress $validator */
            $validator = throw_unless(app(ValidatesAddress::class), InvalidAddressValidator::class);
        }

        $valid = ($validator)($address);

        return $address->updateQuietly([
            'valid' => $valid,
            'validated' => true,
        ]);
    }
}
