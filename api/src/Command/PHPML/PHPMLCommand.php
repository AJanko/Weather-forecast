<?php

namespace App\Command\PHPML;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PHPMLCommand extends AbstractPHPMLCommand
{
    const PROBES_OPTION = 'probes-count';
    const PROBES_MIN = 1;
    const PROBES_MAX = 10;

    protected static $defaultName        = 'ml:cte';
    protected static $defaultDescription = 'Create, train and evaluate model';

    protected function configure()
    {
        $this
            ->addOption(self::PROBES_OPTION, 'p', InputOption::VALUE_REQUIRED, 'Probes quantity for the model', self::PROBES_MIN)
            ->setHelp(
                <<<EOF
Probes quantity for the model. Minimum value is 1, maximum is 10
One probe means that model will be build on data from one specific point of time
Two probes means that two points in time will be chosen (specific hour and an hour before)
Three probes means that three points in time will be chosen (specific hour, hour before and two hours before)
And so on
EOF
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $probes = $input->getOption(self::PROBES_OPTION);
        if (!is_int($probes) || $probes < self::PROBES_MIN || $probes > self::PROBES_MAX) {
            throw new \InvalidArgumentException('Invalid argument provided. It should be integer between 1 and 10');
        }

        $this->repository->initializeModel();

        $count = $this->repository->trainModel($probes);
        $output->writeln("Model trained with $count records");

        $this->repository->evaluateModel($probes);

        return Command::SUCCESS;
    }
}
