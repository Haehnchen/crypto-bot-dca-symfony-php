<?php declare(strict_types=1);

namespace App\Command;

use App\Ccxt\CcxtUtil;
use App\Ccxt\MarketInfoFetcher;
use App\Ccxt\MarketOrderCreator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:market:info')]
class MarketInfoCommand extends Command
{
    public function __construct(private readonly MarketInfoFetcher $marketInfoFetcher)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('exchange', null, InputOption::VALUE_REQUIRED, 'ccxt exchange name');
        $this->addOption('filter', null, InputOption::VALUE_OPTIONAL, 'filter symbol by containing string case insensitive');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $info = $this->marketInfoFetcher->getExchangePairs($input->getOption('exchange'));

        if ($filter = $input->getOption('filter')) {
            $info = array_values(array_filter($info, static fn(array $i) => str_contains(strtolower($i['symbol']), strtolower($filter))));
        }

        $table = new Table($output);
        $table
            ->setHeaders(['Symbol', 'Spot', 'Margin', 'Contract'])
            ->setRows(array_map(static fn(array $i): array => [
                $i['symbol'], $i['spot'], $i['margin'], $i['contract']
            ], $info));
        $table->render();

        return Command::SUCCESS;
    }
}