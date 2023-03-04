<?php

namespace App\Service;

use App\Repository\BigQueryRepository;
use App\Repository\WeatherRepositoryInterface;

class Predictor
{
    private BigQueryRepository         $repository;
    private WeatherRepositoryInterface $weatherApi;

    public function __construct(BigQueryRepository $repository, WeatherRepositoryInterface $weatherApi)
    {
        $this->repository = $repository;
        $this->weatherApi = $weatherApi;
    }

    public function predict(string $lat, string $lon): bool
    {
        $currentWeather = $this->weatherApi->getCurrentWeather($lat, $lon);

        return $this->repository->getWeatherPrediction($currentWeather);
    }
}
