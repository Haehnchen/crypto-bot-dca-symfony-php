<?php declare(strict_types=1);

namespace App\Ccxt;

use App\Exception\AppException;
use ccxt\Exchange;

abstract class CcxtUtil
{
    final private function __construct()
    {
    }

    public static function hasExchangeOrThrowException(string $exchange): void
    {
        if (!in_array($exchange, Exchange::$exchanges, true)) {
            throw new AppException(sprintf('Invalid exchange: "%s" valid %s', $exchange, json_encode(Exchange::$exchanges)));
        }
    }
}