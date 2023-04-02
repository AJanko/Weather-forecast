<?php

namespace App\Predictor;

use App\Entity\WeatherData;

interface PredictorRepositoryInterface
{
    /**
     * Return value depends on the model
     *
     * @return float|bool
     */
    public function predict(WeatherData $currentWeather);
}
