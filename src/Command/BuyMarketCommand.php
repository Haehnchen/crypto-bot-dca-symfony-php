<?php declare(strict_types=1);

namespace App\Command;

use App\Ccxt\CcxtUtil;
use App\Ccxt\MarketOrderCreator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:buy:market')]
class BuyMarketCommand extends Command
{
    public function __construct(private readonly MarketOrderCreator $marketOrderCreator)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('exchange', null, InputOption::VALUE_REQUIRED, 'ccxt exchange name');
        $this->addOption('symbol', null, InputOption::VALUE_REQUIRED, 'BTC/USDT');
        $this->addOption('currency', null, InputOption::VALUE_REQUIRED, 'Use "10.15" to buy this amount of BTC');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exchange = $input->getOption('exchange');
        CcxtUtil::hasExchangeOrThrowException($exchange);

        $symbol = $input->getOption('symbol');
        $currency = $input->getOption('currency');

        $this->marketOrderCreator->order($exchange, $symbol, $currency);

        return Command::SUCCESS;
    }
}