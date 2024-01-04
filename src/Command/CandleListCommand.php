<?php declare(strict_types=1);

namespace App\Command;

use App\Ccxt\CandleFetcher;
use App\Ccxt\DTO\Candle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:candle:list')]
class CandleListCommand extends Command
{
    public function __construct(private readonly CandleFetcher $candleFetcher)
    {
        parent::__construct();
    }


    protected function configure(): void
    {
        $this->addOption('exchange', null, InputOption::VALUE_REQUIRED, 'ccxt exchange name');
        $this->addOption('symbol', null, InputOption::VALUE_REQUIRED, 'BTC/USDT');
        $this->addOption('period', null, InputOption::VALUE_REQUIRED, '1h, 15m, 1d');
        $this->addOption('limit', null, InputOption::VALUE_OPTIONAL, 'candles to fetch', 200);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $limit = (int) ($input->getOption('limit') ?? 200);

        $candles = $this->candleFetcher->getCandles(
            $input->getOption('exchange'),
            $input->getOption('symbol'),
            $input->getOption('period'),
            $limit,
        );

        $table = new Table($output);
        $table
            ->setHeaders(['Timestamp', 'Open', 'High', 'Low', 'Close', 'Volume'])
            ->setRows(array_map(static fn(Candle $c): array => [
                date('d-m-Y H:i', (int) round($c->timestamp / 1000, 0)), $c->open, $c->high, $c->low, $c->close, $c->volume
            ], $candles));

        $table->render();


        return Command::SUCCESS;
    }
}