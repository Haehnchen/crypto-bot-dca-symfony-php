<?php declare(strict_types=1);

namespace App\Ccxt;

readonly class MarketInfoFetcher
{
    public function __construct(private CcxtExchangeFactory $ccxtExchangeFactory)
    {
    }

    public function getExchangePairs(string $exchange): array
    {
        $exchange = $this->ccxtExchangeFactory->createExchange($exchange);

        $markets = $exchange->fetch_markets();

        $markets = array_values(array_filter($markets, static fn(array $i) => ($i['active'] ?? true) === true));

        return array_map(static fn(array $m) => [
            'symbol' => $m['symbol'],
            'spot' => $m['spot'] ?? false,
            'margin' => $m['margin'] ?? false,
            'contract' => ($m['future'] ?? $m['linear'] ?? false),
        ], $markets);
    }
}