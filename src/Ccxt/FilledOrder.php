<?php declare(strict_types=1);

namespace App\Ccxt;

class FilledOrder
{
    public function __construct(private array $ccxtResult)
    {
    }

    public function getId(): string
    {
        return (string) $this->ccxtResult['id'];
    }

    public function getRaw(): array
    {
        return $this->ccxtResult;
    }
}