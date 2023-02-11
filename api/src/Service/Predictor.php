<?php

namespace App\Service;

class Predictor
{
    /** @param BigQuery $bigQuery */
    private $bigQuery;
    /** @param OpenWeather $openWeather */
    private $openWeather;

    public function __construct(BigQuery $bigQuery, OpenWeather $openWeather)
    {
        $this->bigQuery    = $bigQuery;
        $this->openWeather = $openWeather;
    }

    public function predict(string $lat, string $lon): bool
    {
        $currentWeather = $this->openWeather->getCurrentWeather($lat, $lon);
        $mappedWeather  = WeatherDataMapper::map($currentWeather);

        return $this->bigQuery->getWeatherPrediction($mappedWeather);
    }
}
