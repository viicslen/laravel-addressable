<?php

namespace ViicSlen\Addressable\Validators;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use ViicSlen\Addressable\Contracts\ValidatesAddress;
use ViicSlen\Addressable\Enums\UPSRequestType;
use ViicSlen\Addressable\Models\Address;

/**
 * Street-level validation through the UPS Address Validation API, authenticated with OAuth client credentials
 * from `services.ups.client_id` and `services.ups.client_secret`.
 */
class UPSValidator implements ValidatesAddress
{
    /** UPS street-level validation only covers these countries. */
    public const array SUPPORTED_COUNTRIES = ['US', 'PR'];

    public function __invoke(Address $address): ?bool
    {
        if (blank($address->street1) || ! in_array(strtoupper((string) $address->country_code), self::SUPPORTED_COUNTRIES, true)) {
            return null;
        }

        $token = $this->token();

        if ($token === null) {
            return null;
        }

        [$postcode, $extension] = array_pad(explode('-', (string) $address->postal_code, 2), 2, null);

        $response = Http::baseUrl($this->baseUrl())
            ->withToken($token)
            ->acceptJson()
            ->post('/api/addressvalidation/v2/'.UPSRequestType::AddressValidation->value, [
                'XAVRequest' => [
                    'AddressKeyFormat' => array_filter([
                        'AddressLine' => array_values(array_filter([$address->street1, $address->street2], 'filled')),
                        'PoliticalDivision2' => $address->city,
                        'PoliticalDivision1' => $address->state,
                        'PostcodePrimaryLow' => $postcode,
                        'PostcodeExtendedLow' => $extension,
                        'CountryCode' => strtoupper($address->country_code),
                    ], 'filled'),
                ],
            ]);

        // UPS answers an address it cannot read with a 400.
        if ($response->status() === 400) {
            return false;
        }

        if ($response->status() === 401) {
            Cache::forget($this->tokenCacheKey());
        }

        if ($response->failed()) {
            return null;
        }

        return $response->json('XAVResponse.ValidAddressIndicator') !== null;
    }

    protected function token(): ?string
    {
        $token = Cache::get($this->tokenCacheKey());

        if ($token !== null) {
            return $token;
        }

        $response = Http::baseUrl($this->baseUrl())
            ->asForm()
            ->withBasicAuth((string) config('services.ups.client_id'), (string) config('services.ups.client_secret'))
            ->post('/security/v1/oauth/token', ['grant_type' => 'client_credentials']);

        $token = $response->json('access_token');

        if ($response->failed() || ! is_string($token)) {
            return null;
        }

        // Refresh a minute early so a token never expires mid-request.
        Cache::put($this->tokenCacheKey(), $token, max(60, (int) $response->json('expires_in', 0) - 60));

        return $token;
    }

    protected function tokenCacheKey(): string
    {
        return 'addressable:ups-token:'.sha1($this->baseUrl().'|'.config('services.ups.client_id'));
    }

    protected function baseUrl(): string
    {
        return rtrim((string) config('services.ups.base_url', 'https://onlinetools.ups.com'), '/');
    }
}
