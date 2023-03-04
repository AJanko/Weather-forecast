<?php

namespace App\Service;

use App\Repository\BigQueryRepository;
use App\Repository\WeatherApiRepository;

class Predictor
{
    private BigQueryRepository   $repository;
    private WeatherApiRepository $weatherApi;

    public function __construct(BigQueryRepository $repository, WeatherApiRepository $weatherApi)
    {
        $this->repository  = $repository;
        $this->openWeather = $weatherApi;
    }

    public function predict(string $lat, string $lon): bool
    {
        $currentWeather = $this->openWeather->getCurrentWeather($lat, $lon);

        return $this->repository->getWeatherPrediction($currentWeather);
    }
}
