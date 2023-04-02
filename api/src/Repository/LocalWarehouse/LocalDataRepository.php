<?php

namespace App\Repository\LocalWarehouse;

use App\Entity\WeatherData;

class LocalDataRepository
{
    private string $trainingPath;
    private string $testingPath;

    public function __construct(string $trainingPath, string $testingPath)
    {
        $this->trainingPath = $trainingPath;
        $this->testingPath  = $testingPath;
    }

    /** @param array<int, WeatherData> $data */
    public function uploadTrainingData(array $data): int
    {
        return $this->saveData($this->trainingPath, $data);
    }

    /** @param array<int, WeatherData> $data */
    public function uploadTestingData(array $data): int
    {
        return $this->saveData($this->testingPath, $data);
    }

    public function getTrainingData(): array
    {
        $data = $this->getCurrentData($this->trainingPath);

        return $this->mapDataToInstances($data);
    }

    public function getTestingData(): array
    {
        $data = $this->getCurrentData($this->testingPath);

        return $this->mapDataToInstances($data);
    }

    private function saveData(string $path, array $data): int
    {
        $currentData = $this->getCurrentData($path);
        $newData  = array_map(fn(WeatherData $wd) => $wd->toArray(), $data);
        if ($currentData) {
            $newData = array_merge($currentData, $newData);
        }

        $json = json_encode($newData);
        if (!$json) {
            throw new \RuntimeException('b1240c46-df63-4f9d-8fa2-5d374d5e5c5e');
        }

        $result = file_put_contents($path, $json);
        if (false === $result) {
            throw new \RuntimeException('Issue while saving the data into file.');
        }

        return $result;
    }

    private function getCurrentData(string $path): array
    {
        if (!file_exists($path)) {
            return [];
        }

        $json = file_get_contents($path);
        if (!$json) {
            return [];
        }

        return json_decode($json, true);
    }

    /** @return WeatherData[] */
    private function mapDataToInstances(array $data): array
    {
        return array_map(fn (array $wd) => WeatherData::fromArray($wd), $data);
    }
}
