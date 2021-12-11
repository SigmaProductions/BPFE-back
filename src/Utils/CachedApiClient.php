<?php

namespace App\Utils;

use Redis;

class CachedApiClient implements ApiClientInterface
{
    private Redis $redis;

    public function __construct(
      private ApiClient $apiClient,
    ) {
        $this->redis = new Redis();
        $this->redis->connect('redis_db');
    }

    public function post(string $url, array $data): array
    {
        $cached = $this->redis->get(sprintf('%s.%s', $url, md5(json_encode($data))));
        if ($cached === false) {
            $result = $this->apiClient->post($url, $data);
            $this->redis->set(sprintf('%s.%s', $url, md5(json_encode($data))), json_encode($result));
            return $result;
        }
        return json_decode($cached, true);
    }
}