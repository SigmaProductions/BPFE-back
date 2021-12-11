<?php

declare(strict_types=1);

namespace App\Utils;

use GuzzleHttp\Client;

class ApiClient implements ApiClientInterface
{
    private Client $client;

    private array $headers = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
        'BIK-OAPI-Key' => 'ec7d1c806b6141f3ace2ed563dd37f1b',
    ];

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => "https://gateway.oapi.bik.pl",
            'cert'     => ['/app/public/bik.pfx', 'P6Lqeukbc2kT'],
            'curl'     => [CURLOPT_SSLCERTTYPE => 'P12'],
            'verify' => false
        ]);
    }

    public function post(string $url, array $data): array
    {
        $response = $this->client->post($url, [
            'headers' => $this->headers,
            'json' => $data
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}