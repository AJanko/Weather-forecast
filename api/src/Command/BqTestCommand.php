<?php

namespace App\Command;

use App\Service\Predictor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BqTestCommand extends Command
{
    const WILL_RAIN = 'It will rain later today';
    const WONT_RAIN = 'It won\'t rain later today';

    protected static $defaultName = 'bq:test';
    protected static $defaultDescription = 'Test connection to big query API';

    /** @var Predictor */
    private $predictor;

    /** @required */
    public function setDependencies(Predictor $predictor)
    {
        $this->predictor = $predictor;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $result = $this->predictor->predict();

        $output->writeln($result ? self::WILL_RAIN : self::WONT_RAIN);

        return Command::SUCCESS;
    }
}
