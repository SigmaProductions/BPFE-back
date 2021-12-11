<?php

namespace App\Utils;

interface ApiClientInterface
{
    public function post(string $url, array $data): array;
}