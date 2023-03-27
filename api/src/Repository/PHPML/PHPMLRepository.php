<?php

namespace App\Repository\PHPML;

use App\Client\PHPML;
use App\Entity\WeatherData;
use App\Repository\LocalWarehouse\LocalDataRepository;
use Phpml\Metric\Accuracy;
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

    public function trainModel(): int
    {
        $model     = $this->client->getModel();
        $trainData = $this->localDataRepository->getTrainingData();
        [$samples, $targets] = $this->splitIntoSamplesAndTargets($trainData);

        $model->train($samples, $targets);

        $this->client->saveModel($model);

        return count($samples);
    }

    public function evaluateModel(): float
    {
        $testData = $this->localDataRepository->getTestingData();
        [$testSamples, $testTargets] = $this->splitIntoSamplesAndTargets($testData);

        $predictedTargets = array_map(fn(array $sample) => $this->predictFromSamplesArray($sample), $testSamples);

        return Accuracy::score($testTargets, $predictedTargets);
    }

    public function predict(WeatherData $weatherData): float
    {
        return $this->predictFromSamplesArray($weatherData->getSamplesArray());
    }

    private function predictFromSamplesArray(array $samples)
    {
        $model = $this->client->getModel();

        return $model->predict($samples);
    }

    /** @param WeatherData[] $data */
    private function splitIntoSamplesAndTargets(array $data): array
    {
        $samples = array_map(fn(WeatherData $wd) => $wd->getSamplesArray(), $data);
        $targets = array_map(fn(WeatherData $wd) => $wd->getTarget(), $data);

        return [$samples, $targets];
    }
}
