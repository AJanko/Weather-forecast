<?php

namespace App\Repository\WeatherDataSource;

use App\Entity\WeatherData;

interface WeatherRepositoryInterface
{
    public function getCurrentWeather(string $lat, string $lon): WeatherData;

    /** @return WeatherData[] */
    public function getHistoricData(string $lat, string $lon, string $start, string $end, int $interval = 60): array;
}
