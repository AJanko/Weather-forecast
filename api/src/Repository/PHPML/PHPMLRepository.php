<?php

namespace App\Repository\PHPML;

use App\Client\PHPML;
use App\Entity\ModelEntityInterface;
use App\Predictor\PredictorRepositoryInterface;
use App\Renderer\PlotRenderer;
use App\Repository\LocalWarehouse\LocalDataRepository;
use Phpml\Regression\LeastSquares;

class PHPMLRepository implements PredictorRepositoryInterface
{
    private PHPML               $client;
    private LocalDataRepository $localDataRepository;
    private PlotRenderer        $plotRenderer;

    public function __construct(PHPML $client, LocalDataRepository $localDataRepository, PlotRenderer $plotRenderer)
    {
        $this->client              = $client;
        $this->localDataRepository = $localDataRepository;
        $this->plotRenderer        = $plotRenderer;
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

    public function evaluateModel(): void
    {
        $testData = $this->localDataRepository->getTestingData();
        [$testSamples, $testTargets] = $this->splitIntoSamplesAndTargets($testData);

        $predictedTargets = array_map(fn(array $sample) => $this->predictFromSamplesArray($sample), $testSamples);

        $this->plotRenderer->drawTargetsCompare($testTargets, $predictedTargets);
    }

    public function predict(ModelEntityInterface $currentData): float
    {
        return $this->predictFromSamplesArray($currentData->getSamplesArray());
    }

    private function predictFromSamplesArray(array $samples): float
    {
        $model = $this->client->getModel();

        return $model->predict($samples);
    }

    /** @param ModelEntityInterface[] $data */
    private function splitIntoSamplesAndTargets(array $data): array
    {
        $samples = array_map(fn(ModelEntityInterface $me) => $me->getSamplesArray(), $data);
        $targets = array_map(fn(ModelEntityInterface $me) => $me->getTarget(), $data);

        return [$samples, $targets];
    }
}
