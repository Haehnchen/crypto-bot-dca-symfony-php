# DCA Crypto Currency Bot

Provide DCA strategies and a simple cronjob to buy on every support exchange via https://github.com/ccxt/ccxt (binance, coinbase, bybit, ...)

Based on PHP 8.2 + Symfony

## Install

```bash
composer install
```

Provide exchange API keys (think of correct permissions)

```bash
cp exchanges.json.dist exchanges.json
```

## Examples

Work in progress for more

### Simple cronjob task

Buy 15.23 USD(T) of BTC at current market price

```bash
bin/console app:buy:market --exchange=binance --symbol=BTC/USDT --currency=15.23
```

### Market Info

```bash
bin/console app:market:info --exchange=binance
bin/console app:market:info --exchange=binance --filter=btc
```