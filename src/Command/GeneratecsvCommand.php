<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Rs\JsonLines\JsonLines;
use App\Services\JSONLinesReader;
use App\Services\OrderHelper;
use App\Services\CSVHelper;

class GeneratecsvCommand extends Command
{
    protected static $defaultName = 'generatecsv';
    protected static $defaultDescription = 'Add a short description for your command';

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io -> note('importing JSON linesfrom https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl...');
        $jsonReader = new JSONLinesReader();
        $ordersArray = $jsonReader -> readJSONLines('https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl');

        $io -> note('creating CSV...');
        $orderHelper = new OrderHelper();
        $orderObjectArray = $orderHelper -> generateOrders($ordersArray);
        // var_dump($orderObjectArray);
        $csvHelper = new CSVHelper();
        $exportableArray = $csvHelper -> generateExportableOrder($orderObjectArray);

        $fp = fopen('out.csv', 'w');
          
        foreach ($exportableArray as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);

        $io->success('Success!');

        return Command::SUCCESS;
    }
}
