<?php declare(strict_types=1);

namespace App\Ccxt\DTO;

readonly class Candle
{
    public function __construct(
        public int $timestamp,
        public float $open,
        public float $high,
        public float $low,
        public float $close,
        public float $volume
    ) {
    }
}