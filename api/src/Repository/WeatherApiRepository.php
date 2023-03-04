<?php

namespace App\Repository;

use App\Entity\WeatherData;
use App\Service\WeatherApi;

class WeatherApiRepository
{
    private const HISTORY_URI = "https://api.weatherapi.com/v1/history.json?lang=pl&q=%s&unixdt=%s&unixend_dt=%s&tp=%s";

    private WeatherApi $client;

    public function __construct(WeatherApi $client)
    {
        $this->client = $client;
    }

    /**
     * Unfortunately history data isn't available for free
     *
     * @return array<int, WeatherData>
     */
    public function getHistoricData(string $lat, string $lon, string $start, string $end, int $interval = 60): array
    {
        $data = $this->client->requestData(
            sprintf(self::HISTORY_URI, "$lat,$lon", $start, $end, $interval)
        );

        $hourData = [];
        foreach ($data['forecast']['forecastday'] as $forecastday) {
            $hourData = array_merge($hourData, $forecastday['hour']);
        }

        return array_map([$this, 'createWeatherDataInstance'], $hourData);
    }

    private function createWeatherDataInstance(array $data): WeatherData
    {
        return new WeatherData(
            $data['temp_c'],
            $data['feelslike_c'],
            $data['humidity'],
            $data['cloud'],
            $data['wind_kph'],
            $data['gust_kph'],
            $data['precip_mm'],
            $data['vis_km'],
            $data['time_epoch'],
            (bool)$data['will_it_rain']
        );
    }
}
