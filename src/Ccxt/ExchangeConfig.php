<?php declare(strict_types=1);

namespace App\Ccxt;

readonly class ExchangeConfig
{
    public function __construct(
        public string $exchange,
        public string $apiKey,
        public string $apiSecret
    ) {
    }
}