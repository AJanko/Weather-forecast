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

    public function trainModel(int $probes): int
    {
        $model     = $this->client->getModel();
        $trainData = $this->localDataRepository->getTrainingData();
        [$samples, $targets] = $this->splitIntoSamplesAndTargets($probes, $trainData);

        $model->train($samples, $targets);

        $this->client->saveModel($model);

        return count($samples);
    }

    /**
     * It has side effect of drawing plot
     */
    public function evaluateModel(int $probes): void
    {
        $testData = $this->localDataRepository->getTestingData();
        [$testSamples, $testTargets] = $this->splitIntoSamplesAndTargets($probes, $testData);

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
    private function splitIntoSamplesAndTargets(int $probes, array $data): array
    {
        $hour = 3600;
        /** @var array<int, ModelEntityInterface> $indexedData */
        $indexedData = array_combine(
            array_map(fn(ModelEntityInterface $me) => $me->getTimestamp(), $data),
            $data,
        );

        $samples = [];
        foreach ($data as $item) {
            $sampleItem = $item->getSamplesArray();
            $i = 1;
            $skip = false;
            while ($i < $probes) {
                $toMerge = $indexedData[$item->getTimestamp() - $i * $hour] ?? null;
                if (!$toMerge) {
                    $skip = true;
                    break;
                }

                $sampleItem = [...$sampleItem, ...$toMerge->getSamplesArray()];
            }

            // When data isn't complete then it will be skipped
            if (!$skip) {
                $samples[] = $sampleItem;
            }
        }

        $targets = array_map(fn(ModelEntityInterface $me) => $me->getTarget(), $data);

        return [$samples, $targets];
    }
}
