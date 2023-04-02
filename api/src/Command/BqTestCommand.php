<?php

namespace App\Command;

use App\Predictor\Predictor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BqTestCommand extends Command
{
    const WILL_RAIN = 'It will rain later today';
    const WONT_RAIN = 'It won\'t rain later today';

    protected static $defaultName        = 'bq:test';
    protected static $defaultDescription = 'Test connection to big query API';

    private Predictor $predictor;

    /** @required */
    public function setDependencies(Predictor $predictor)
    {
        $this->predictor = $predictor;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Command defined only to make testing easier, thus coords are predefined
        $lat    = 50.049683;
        $lon    = 19.944544;
        $result = $this->predictor->predict($lat, $lon);

        $output->writeln($result ? self::WILL_RAIN : self::WONT_RAIN);

        return Command::SUCCESS;
    }
}
