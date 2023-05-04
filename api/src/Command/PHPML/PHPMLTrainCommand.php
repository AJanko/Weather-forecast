<?php

namespace App\Command\PHPML;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PHPMLTrainCommand extends AbstractPHPMLCommand
{
    protected static $defaultName        = 'ml:train';
    protected static $defaultDescription = 'Train model using stored train data';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $count = $this->repository->trainModel(1);

        $output->writeln("Model trained with $count records");

        return Command::SUCCESS;
    }
}
