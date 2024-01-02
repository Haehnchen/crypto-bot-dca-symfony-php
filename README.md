# DCA Crypto Currency Bot

Provide DCA strategies and a simple cronjob to buy on every support exchange via https://github.com/ccxt/ccxt (binance, coinbase, bybit, ...)

Based on PHP 8.2 + Symfony

## Install

´´´
composer install
´´´

Provide exchange API keys (think of correct permissions)

´´´
cp exchanges.json.dist exchanges.json
´´´

## Examples

Work in progress for more

### Simple cronjob task

Buy 15.23 USD(T) of BTC at current market price

´´´
bin/console app:buy:market --exchange=binance --symbol=BTC/USDT --currency=15.23
´´´