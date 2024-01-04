<?php declare(strict_types=1);

namespace App\Ccxt;

use App\Ccxt\DTO\Candle;
use ccxt\NotSupported;

readonly class CandleFetcher
{
    public function __construct(private CcxtExchangeFactory $ccxtExchangeFactory)
    {
    }

    /**
     * @param string $period 15m , 1h, 1d
     * @return Candle[]
     */
    public function getCandles(string $exchange, string $symbol, string $period, int $limit): array
    {
        $exchangeInstance = $this->ccxtExchangeFactory->createExchange($exchange);

        $since = match($period[-1]) {
            'm' => time() - (substr($period, 0, -1) * 60 * $limit),
            'h' => time() - ((substr($period, 0, -1) * 60) * 60 * $limit),
            'd' => time() - (substr($period, 0, -1) * 60 * 60 * 24 * $limit),
            default => throw new \InvalidArgumentException('No supported period:' . $period)
        };

        return array_values(array_map(
            static fn(array $c) => new Candle(...array_combine(['timestamp', 'open', 'high', 'low', 'close', 'volume'], $c)),
            $exchangeInstance->fetch_ohlcv($symbol, $period, $since * 1000)
        ));
    }
}