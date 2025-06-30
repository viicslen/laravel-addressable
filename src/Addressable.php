<?php

namespace ViicSlen\Addressable;

use Sokil\IsoCodes\Database\Countries;
use Sokil\IsoCodes\Database\Countries\Country;
use Sokil\IsoCodes\Database\Currencies;
use Sokil\IsoCodes\Database\Currencies\Currency;
use Sokil\IsoCodes\Database\LanguagesInterface;
use Sokil\IsoCodes\IsoCodesFactory;
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

    public function languages(): LanguagesInterface
    {
        return app(IsoCodesFactory::class)->getLanguages();
    }

    public function currencies(): Currencies
    {
        return app(IsoCodesFactory::class)->getCurrencies();
    }

    public function countries(): Countries
    {
        return app(IsoCodesFactory::class)->getCountries();
    }

    public function currency(string $currencyCode): ?Currency
    {
        return $this->currencies()->getByLetterCode(strtoupper($currencyCode));
    }

    public function country(string $countryCode): ?Country
    {
        return $this->countries()->getByAlpha2(strtoupper($countryCode));
    }
}
