<?php

namespace App\Command\PHPML;

use App\Repository\PHPML\PHPMLRepository;
use Symfony\Component\Console\Command\Command;

abstract class AbstractPHPMLCommand extends Command
{
    protected PHPMLRepository $repository;

    /** @required */
    public function setDependencies(PHPMLRepository $repository): void
    {
        $this->repository = $repository;
    }
}
