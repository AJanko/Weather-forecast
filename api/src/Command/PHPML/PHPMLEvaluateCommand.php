<?php

namespace App\Command\PHPML;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PHPMLEvaluateCommand extends AbstractPHPMLCommand
{
    protected static $defaultName        = 'ml:evaluate';
    protected static $defaultDescription = 'Evaluate model';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->repository->evaluateModel(1);

        return Command::SUCCESS;
    }
}
