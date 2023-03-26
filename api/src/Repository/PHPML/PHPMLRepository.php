<?php

namespace App\Repository\PHPML;

use App\Client\PHPML;
use App\Entity\WeatherData;
use App\Repository\LocalWarehouse\LocalDataRepository;
use Phpml\Regression\LeastSquares;

class PHPMLRepository
{
    private PHPML               $client;
    private LocalDataRepository $localDataRepository;

    public function __construct(PHPML $client, LocalDataRepository $localDataRepository)
    {
        $this->client              = $client;
        $this->localDataRepository = $localDataRepository;
    }

    public function initializeModel(): void
    {
        $model = new LeastSquares();
        $this->client->saveModel($model);
    }

    public function trainModel(): void
    {
        $model     = $this->client->getModel();
        $trainData = $this->localDataRepository->getTrainingData();
        [$samples, $targets] = $this->splitIntoSamplesAndTargets($trainData);

        $model->train($samples, $targets);
    }

    public function predict(WeatherData $weatherData): float
    {
        $model = $this->client->getModel();

        return $model->predict($weatherData->getSamplesArray());
    }

    /** @param WeatherData[] $data */
    private function splitIntoSamplesAndTargets(array $data): array
    {
        $samples = array_map(fn(WeatherData $wd) => $wd->getSamplesArray(), $data);
        $targets = array_map(fn(WeatherData $wd) => $wd->getTarget(), $data);

        return [$samples, $targets];
    }
}
