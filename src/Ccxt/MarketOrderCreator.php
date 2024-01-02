<?php declare(strict_types=1);

namespace App\Ccxt;


use App\Exception\OrderException;
use App\Exception\ExchangeException;
use App\Exception\TickerException;
use ccxt\Exchange;
use ccxt\ExchangeError;
use ccxt\InvalidOrder;
use Psr\Log\LoggerInterface;

readonly class MarketOrderCreator
{
    public function __construct(
        private ApiKeyStorage $apiKeyStorage,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @param string $symbol "BTC/USDT"
     * @param string $wantCurrencyAmount "10" as USDT (second value)
     */
    public function order(string $exchange, string $symbol, string $wantCurrencyAmount): FilledOrder
    {
        CcxtUtil::hasExchangeOrThrowException($exchange);

        $config = $this->apiKeyStorage->getExchange($exchange);

        if (!class_exists('ccxt\\' . $exchange)) {
            throw new \InvalidArgumentException('class ccxt\\' . $exchange . ' not found');
        }

        /** @var Exchange $ccxtExchange */
        $ccxtExchange = new ('ccxt\\' . $exchange)([
            'apiKey' => $config->apiKey,
            'secret' => $config->apiSecret,
        ]);

        $markets = $ccxtExchange->load_markets();

        $market = null;
        foreach ($markets as $myMarket) {
            if ($myMarket['symbol'] === $symbol) {
                $market = $myMarket;
            }
        }

        $ticker = $ccxtExchange->fetch_ticker($market['symbol']);
        if (!isset($ticker['bid'])) {
            throw new TickerException('no bid price found');
        }

        $amount = bcdiv($wantCurrencyAmount, (string) $ticker['bid'], $market['precision']['base'] ?? 8);
        $amountPrecision = $ccxtExchange->amount_to_precision($market['symbol'], $amount);

        try {
            $result = $ccxtExchange->create_market_buy_order($market['symbol'], $amountPrecision);
        } catch (InvalidOrder $e) {
            $this->logger->error('Invalid order: ' . $e->getMessage());
            throw new OrderException('Invalid order: ' . $e->getMessage());
        } catch (ExchangeError $e) {
            $this->logger->error('Invalid order: ' . $e->getMessage());
            throw new ExchangeException('Invalid exchange order response: ' . $e->getMessage());
        }

        return new FilledOrder($result);
    }
}