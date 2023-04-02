<?php

namespace App\Command\PHPML;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PHPMLInitializeCommand extends AbstractPHPMLCommand
{
    protected static $defaultName        = 'ml:create';
    protected static $defaultDescription = 'Initialize model';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->repository->initializeModel();

        return Command::SUCCESS;
    }
}
