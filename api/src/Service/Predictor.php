<?php

namespace App\Service;

use App\Repository\BigQueryRepository;
use App\Repository\OpenWeatherRepository;

class Predictor
{
    private BigQueryRepository    $repository;
    private OpenWeatherRepository $openWeather;

    public function __construct(BigQueryRepository $repository, OpenWeatherRepository $openWeather)
    {
        $this->repository  = $repository;
        $this->openWeather = $openWeather;
    }

    public function predict(string $lat, string $lon): bool
    {
        $currentWeather = $this->openWeather->getCurrentWeather($lat, $lon);

        return $this->repository->getWeatherPrediction($currentWeather);
    }
}
