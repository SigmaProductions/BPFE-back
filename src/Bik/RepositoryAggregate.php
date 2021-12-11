<?php
declare(strict_types=1);

namespace App\Bik;

use App\DTO\Address;
use App\Utils\CachedApiClient;
use Symfony\Component\Yaml\Yaml;

class RepositoryAggregate
{
    private array $configuration;

    public function __construct(
        private CachedApiClient $apiClient,
    )
    {
        $this->configuration = Yaml::parse(file_get_contents(__DIR__ . '/../../../config/bik.yaml'));
    }

    public function get(Address $address, string $primaryParam, ?string $secondaryParam = null): array
    {
        $filtered = current(array_filter(
            $this->configuration,
            fn($singeConfig) => in_array($primaryParam, $singeConfig['primary']['values'])
        ));

        $date = [
            'address'  => [
                'code' => $address->postalCode,
                'city' => "Łódź",
                'street' => $address->street,
                'building_number' => $address->number,
            ],
            $filtered['primary']['param-name'] => $primaryParam,
            'size' => $filtered['grid-size'],
        ];

        if ($secondaryParam) {
            $date = array_merge($date, [$filtered['secondary']['param-name'] => $secondaryParam]);
        }
        try {
            $request = $this->apiClient->post(
                $filtered['uri'],
                $date
            );
        } catch (\Exception $e) {
            return ['errror' => $e->getMessage()];
        }

        if (isset($filtered['result-from-array'])) {
            $result = current($request[$filtered['result-from-array']]);
        } else {
            $result = $request;
        }

        if (isset($secondaryParam)) {
            $result = ["$primaryParam/$secondaryParam" => $result[$filtered['result']]];
        } else {
            $result = [$primaryParam => $result[$filtered['result']]];
        }

        if (isset($filtered['result-unpack'])) {
            $result = $result[$primaryParam];
        }

        return $result;
    }
}
