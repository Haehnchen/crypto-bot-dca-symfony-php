<?php declare(strict_types=1);

namespace App\Ccxt;

class BalanceFetcher
{
    public function __construct(
        private CcxtExchangeFactory $ccxtExchangeFactory,
        private ApiKeyStorage $apiKeyStorage,
    ) {
    }

    public function getBalance(string $exchange): array
    {
        $config = $this->apiKeyStorage->getExchange($exchange);

        $exchange = $this->ccxtExchangeFactory->createExchange($exchange, [
            'apiKey' => $config->apiKey,
            'secret' => $config->apiSecret,
        ]);

        $balances = array_filter($exchange->fetch_balance()['total'] ?? [], static fn(float $i) => $i > 0.0);

        return array_map(static fn($key, $value) => [
            'asset' => $key,
            'amount' => $value,
        ], array_keys($balances), array_values($balances));
    }
}