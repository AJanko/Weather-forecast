<?php

namespace App\Service;

use App\Repository\BigQueryRepository;

class Predictor
{
    private BigQueryRepository $repository;
    private OpenWeather        $openWeather;

    public function __construct(BigQueryRepository $repository, OpenWeather $openWeather)
    {
        $this->repository  = $repository;
        $this->openWeather = $openWeather;
    }

    public function predict(string $lat, string $lon): bool
    {
        $currentWeather = $this->openWeather->getCurrentWeather($lat, $lon);
        $mappedWeather  = WeatherDataMapper::map($currentWeather);

        return $this->repository->getWeatherPrediction($mappedWeather);
    }
}
