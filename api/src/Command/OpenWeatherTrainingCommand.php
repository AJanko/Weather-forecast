<?php

namespace App\Command;

use App\Repository\BigQueryRepository;
use App\Repository\OpenWeatherRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class OpenWeatherTrainingCommand extends Command
{
    protected static $defaultName        = 'ow:train';
    protected static $defaultDescription = 'Upload training data to bigquery';

    private BigQueryRepository    $bigQueryRepository;
    private OpenWeatherRepository $openWeatherRepository;

    private string $lat;
    private string $lon;

    /** @required */
    public function setUpDependencies(
        BigQueryRepository $bigQueryRepository,
        OpenWeatherRepository $openWeatherRepository,
        string $lat,
        string $lon
    ) {
        $this->bigQueryRepository    = $bigQueryRepository;
        $this->openWeatherRepository = $openWeatherRepository;
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

        $historicData = $this->openWeatherRepository->getHistoricData($this->lat, $this->lon, $startDate, $endDate);
        $this->bigQueryRepository->uploadTrainingData($historicData);

        return Command::SUCCESS;
    }
}
