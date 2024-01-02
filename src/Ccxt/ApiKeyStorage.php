<?php declare(strict_types=1);

namespace App\Ccxt;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class ApiKeyStorage
{
    public function __construct(
        #[Autowire('%kernel.project_dir%/exchanges.json')]
        private string $configFile
    ) {
    }

    public function getExchange(string $exchange): ExchangeConfig
    {
        foreach (json_decode(file_get_contents($this->configFile, true), true) as $config) {
            if ($config['exchange'] === $exchange) {
                return new ExchangeConfig(...$config);
            }
        }

        throw new \InvalidArgumentException();
    }
}