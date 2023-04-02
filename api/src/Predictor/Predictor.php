<?php

namespace App\Predictor;

use App\Repository\WeatherDataSource\WeatherRepositoryInterface;

class Predictor
{
    private WeatherRepositoryInterface   $weatherApi;
    private PredictorRepositoryInterface $predictorRepository;

    public function __construct(
        PredictorRepositoryInterface $predictorRepository,
        WeatherRepositoryInterface $weatherApi
    ) {
        $this->weatherApi          = $weatherApi;
        $this->predictorRepository = $predictorRepository;
    }

    public function predict(string $lat, string $lon): float
    {
        $currentWeather = $this->weatherApi->getCurrentWeather($lat, $lon);

        return $this->predictorRepository->predict($currentWeather);
    }
}
