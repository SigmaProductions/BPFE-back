<?php

declare(strict_types=1);

namespace App\DTO;

class Address
{
    public function __construct(
        public string $street,
        public int $number,
        public int $postalCode,
    )
    {
    }
}