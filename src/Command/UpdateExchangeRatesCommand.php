<?php

namespace App\Command;

use App\Entity\Currency;
use App\Http\NBPApiClient;
use App\Repository\CurrencyRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:update-exchange-rates',
    description: 'Fetches and updates exchange rates in database',
)]
class UpdateExchangeRatesCommand extends Command
{
    private $currencyRepo;
    
    private $client;

    public function __construct(CurrencyRepository $currencyRepo, NBPApiClient $client)
    {
        $this->currencyRepo = $currencyRepo;
        $this->client = $client;

        parent::__construct();
    }
    
    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $rates = $this->client->fetchOnlyRates();
        
        $countRates = count($rates);
        for($i = 0; $i < $countRates; $i++) {
            $rate = $rates[$i];

            $currency = $this->currencyRepo->findOneBy([
                'currency_code' => $rate['code']
            ]);

            if($currency) {
                $currency->setName($rate['currency']);
                $currency->setExchangeRate($rate['mid']);

                $io->text("Updated currency {$rate['code']} exchange rate to {$rate['mid']}.");
            } else {
                $currency = new Currency($rate['currency'], $rate['code'], $rate['mid']);
                $io->text("Created new currency {$rate['code']} with exchange rate {$rate['mid']}.");
            }

            $this->currencyRepo->add($currency, $i == $countRates - 1);
        }

        $io->success('Successfully updated exchange rates.');

        return Command::SUCCESS;
    }
}
