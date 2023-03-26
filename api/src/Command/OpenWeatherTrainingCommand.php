<?php

namespace App\Command;

use App\Repository\DataWarehouse\BigQueryRepository;
use App\Repository\LocalWarehouse\LocalDataRepository;
use App\Repository\WeatherDataSource\WeatherRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class OpenWeatherTrainingCommand extends Command
{
    protected static $defaultName        = 'ow:train';
    protected static $defaultDescription = 'Upload training data to bigquery';

    private LocalDataRepository        $localDataRepository;
    private WeatherRepositoryInterface $weatherApiRepository;

    private string $lat;
    private string $lon;

    /** @required */
    public function setUpDependencies(
        LocalDataRepository $localDataRepository,
        WeatherRepositoryInterface $repository,
        string $lat,
        string $lon
    ) {
        $this->localDataRepository  = $localDataRepository;
        $this->weatherApiRepository = $repository;

        $this->lat = $lat;
        $this->lon = $lon;
    }

    protected function configure()
    {
        parent::configure();

        $this
            ->addOption('start', 'st', InputOption::VALUE_REQUIRED)
            ->addOption('end', 'end', InputOption::VALUE_REQUIRED)
            ->addOption('test-data', 't', InputOption::VALUE_NEGATABLE, '', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $start = $input->getOption('start');
        $end   = $input->getOption('end');
        if (!$start || !$end) {
            $output->writeln('Missing input data');

            return Command::FAILURE;
        }

        $startDate = (new \DateTime("- $start days"))->getTimestamp();
        $endDate   = (new \DateTime("- $end days"))->getTimestamp();

        $historicData = $this->weatherApiRepository->getHistoricData($this->lat, $this->lon, $startDate, $endDate);

        $test = $input->getOption('test-data');
        if ($test) {
            $this->localDataRepository->uploadTestingData($historicData);
        } else {
            $this->localDataRepository->uploadTrainingData($historicData);
        }

        return Command::SUCCESS;
    }
}
