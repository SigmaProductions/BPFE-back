<?php

declare(strict_types=1);

namespace App\Bik;

use App\DTO\Address;
use App\Utils\CachedApiClient;

class PurchasingPowerRepository
{
    public function __construct(
        private CachedApiClient $httpClient,
    )
    {
    }

    public function getPurchasingPower(Address $address): array
    {
        return $this->httpClient->post(
            'bik-api-7/sila-nabywcza-adres',
            [
                'address'  => [
                    'code' => $address->postalCode,
                    'city' => "Łódź",
                    'street' => $address->street,
                    'building_number' => $address->number,
                ],
                'silaNabywcza' => 'NapojeAlkoholowe',
                'size' => 100
            ]
        );
    }
}