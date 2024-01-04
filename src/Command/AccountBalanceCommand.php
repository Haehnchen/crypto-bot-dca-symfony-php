<?php declare(strict_types=1);

namespace App\Command;

use App\Ccxt\BalanceFetcher;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:account:balance')]
class AccountBalanceCommand extends Command
{
    public function __construct(private readonly BalanceFetcher $balanceFetcher)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('exchange', null, InputOption::VALUE_REQUIRED, 'ccxt exchange name');
        $this->addOption('filter', null, InputOption::VALUE_OPTIONAL, 'filter asset by containing string case insensitive');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $balances = $this->balanceFetcher->getBalance($input->getOption('exchange'));

        if ($filter = $input->getOption('filter')) {
            $balances = array_values(array_filter($balances, static fn(array $i) => str_contains(strtolower($i['asset']), strtolower($filter))));
        }

        $table = new Table($output);
        $table
            ->setHeaders(['Asset', 'Amount'])
            ->setRows(array_map(static fn(array $i): array => [
                $i['asset'], $i['amount']
            ], $balances));

        $table->render();

        return Command::SUCCESS;
    }
}