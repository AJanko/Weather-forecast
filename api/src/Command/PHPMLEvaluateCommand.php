<?php

namespace App\Command;

use App\Repository\PHPML\PHPMLRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PHPMLEvaluateCommand extends Command
{
    private PHPMLRepository $repository;

    protected static $defaultName        = 'ml:evaluate';
    protected static $defaultDescription = 'Evaluate model';

    /** @required */
    public function setDependencies(PHPMLRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $result = $this->repository->evaluateModel();

        $output->writeln("The model evaluation rate is: $result");

        return Command::SUCCESS;
    }
}
