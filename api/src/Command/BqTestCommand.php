<?php

namespace App\Command;

use App\Service\BigQuery;
use App\Service\OpenWeather;
use App\Service\WeatherDataMapper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BqTestCommand extends Command
{
    const WILL_RAIN = 'It will rain later today';
    const WONT_RAIN = 'It won\'t rain later today';

    protected static $defaultName = 'bq:test';
    protected static $defaultDescription = 'Test connection to big query API';

    /** @param BigQuery $bigQuery */
    private $bigQuery;
    /** @param OpenWeather $openWeather */
    private $openWeather;

    /** @required */
    public function setDependencies(BigQuery $bigQuery, OpenWeather $openWeather)
    {
        $this->bigQuery    = $bigQuery;
        $this->openWeather = $openWeather;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $lat = 50.049683;
        $lon = 19.944544;

        $currentWeather = $this->openWeather->getCurrentWeather($lat, $lon);
        $mappedWeather  = WeatherDataMapper::map($currentWeather);

        $result = $this->bigQuery->getWeatherPrediction($mappedWeather);

        $output->writeln($result ? self::WILL_RAIN : self::WONT_RAIN);

        return Command::SUCCESS;
    }
}
