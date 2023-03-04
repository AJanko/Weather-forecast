<?php

namespace App\Repository;

use App\Client\WeatherApi;
use App\Entity\WeatherData;

class WeatherApiRepository implements WeatherRepositoryInterface
{
    private const HISTORY_WEATHER_URI = "https://api.weatherapi.com/v1/history.json?lang=pl&q=%s&unixdt=%s&unixend_dt=%s&tp=%s";
    private const CURRENT_WEATHER_URI = "https://api.weatherapi.com/v1/current.json?lang=pl&q=%s";

    private WeatherApi $client;

    public function __construct(WeatherApi $client)
    {
        $this->client = $client;
    }

    public function getCurrentWeather(string $lat, string $lon): WeatherData
    {
        $data = $this->client->requestData(
            sprintf(self::CURRENT_WEATHER_URI, "$lat,$lon")
        );

        return $this->createCurrentWeatherDataInstance($data['current']);
    }

    /**
     * Unfortunately history data isn't available for free for this API
     * Currently there is free tier for 14 days (from 4.03)
     *
     * @return WeatherData[]
     */
    public function getHistoricData(string $lat, string $lon, string $start, string $end, int $interval = 60): array
    {
        $data = $this->client->requestData(
            sprintf(self::HISTORY_WEATHER_URI, "$lat,$lon", $start, $end, $interval)
        );

        $hourData = [];
        foreach ($data['forecast']['forecastday'] as $forecastday) {
            $hourData = array_merge($hourData, $forecastday['hour']);
        }

        return array_map([$this, 'createWeatherDataInstance'], $hourData);
    }

    private function createCurrentWeatherDataInstance(array $data): WeatherData
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
            $data['last_updated_epoch']
        );
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
