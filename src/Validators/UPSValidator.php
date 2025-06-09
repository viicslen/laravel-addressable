<?php

use ViicSlen\Addressable\Models\AddressBook\Address;

class UPSValidator
{
    public function __invoke(Address $address): bool
    {
        $baseUrl = config('services.ups.base_url');
        $regionalRequestIndicator = config('services.ups.regional_request_indicator', true);
        $maximumCandidateListSize = config('services.ups.maximum_candidate_list_size', 2);

        $fullUri = sprintf(
            '%s%s?regionalrequestIndicator=%s&maximumcandidatelistsize=%d',
            $baseUrl, UPSRequestType::AddressValidation->value, $regionalRequestIndicator, $maximumCandidateListSize
        );

        $body = [
            'XAVRequest' => [
                'AddressKeyFormat' => [
                    'ConsigneeName' => 'MyListerHub',
                    'AddressLine' => [
                        $address->street1,
                        $address->street2,
                    ],
                    'PoliticalDivision2' => $address->city,
                    'PoliticalDivision1' => $address->country,
                    'PostcodePrimaryLow' => $address->postal_code,
                    'CountryCode' => $address->country_code,
                ],
            ],
        ];

        $response = Http::withHeaders([
            'AccessLicenseNumber' => config('services.ups.access_license_number', ''),
            'Username' => config('services.ups.username', ''),
            'Password' => config('services.ups.password', ''),
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => 'Origin, X-Requested-With, Content-Type, Accept',
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($fullUri, $body);

        return $response->successful() && $response->json('XAVResponse.ValidAddressIndicator') !== null;
    }
}
