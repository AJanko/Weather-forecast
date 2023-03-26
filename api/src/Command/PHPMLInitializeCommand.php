<?php

namespace App\Command;

use App\Repository\PHPML\PHPMLRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PHPMLInitializeCommand extends Command
{
    private PHPMLRepository $repository;

    protected static $defaultName        = 'ml:create';
    protected static $defaultDescription = 'Initialize model';

    /** @required */
    public function setDependencies(PHPMLRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->repository->initializeModel();

        return Command::SUCCESS;
    }
}
