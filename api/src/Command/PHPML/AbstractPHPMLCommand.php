<?php

namespace App\Command\PHPML;

use App\Repository\PHPML\PHPMLRepository;
use Symfony\Component\Console\Command\Command;

class AbstractPHPMLCommand extends Command
{
    protected PHPMLRepository $repository;

    /** @required */
    protected function setDependencies(PHPMLRepository $repository): void
    {
        $this->repository = $repository;
    }
}
