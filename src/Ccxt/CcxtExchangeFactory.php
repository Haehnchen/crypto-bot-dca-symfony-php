<?php declare(strict_types=1);

namespace App\Ccxt;

use ccxt\Exchange;

class CcxtExchangeFactory
{
    public function createExchange(string $exchange, array $options = []): Exchange
    {
        CcxtUtil::hasExchangeOrThrowException($exchange);

        if (!class_exists('ccxt\\' . $exchange)) {
            throw new \InvalidArgumentException('class ccxt\\' . $exchange . ' not found');
        }

        /** @var Exchange $ccxtExchange */
        return new ('ccxt\\' . $exchange)($options);
    }
}