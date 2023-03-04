<?php

namespace App\Repository;

use App\Entity\WeatherData;
use App\Service\OpenWeather;

class OpenWeatherRepository implements WeatherRepositoryInterface
{
    private const CURRENT_WEATHER_URI = "https://api.openweathermap.org/data/2.5/weather?lat=%s&lon=%s";
    private const HISTORY_WEATHER_URI = "https://history.openweathermap.org/data/2.5/history/city?lat=%s&lon=%s&start=%s&end=%s&type=hour";

    private OpenWeather $client;

    public function __construct(OpenWeather $client)
    {
        $this->client = $client;
    }

    public function getCurrentWeather(string $lat, string $lon): WeatherData
    {
        throw new \RuntimeException('Open weather current data does not have all necessary data currently');

        $data = $this->client->requestData(
            sprintf(self::CURRENT_WEATHER_URI, $lat, $lon)
        );

        return $this->createWeatherDataInstance($data);
    }

    /**
     * Unfortunately history data isn't available for free
     *
     * @return WeatherData[]
     */
    public function getHistoricData(string $lat, string $lon, string $start, string $end, int $interval = 60): array
    {
        throw new \RuntimeException('Open weather historic data is not available for free');

        $data = $this->client->requestData(
            sprintf(self::HISTORY_WEATHER_URI, $lat, $lon, $start, $end)
        );

        return array_map([$this, 'createWeatherDataInstance'], $data);
    }

    private function createWeatherDataInstance(array $data): WeatherData
    {
        return new WeatherData(
            $data['main']['temp'],
            $data['main']['feels_like'],
            $data['main']['humidity'],
            $data['clouds']['all'],
            $data['wind']['speed'],
            $data['wind']['gust'],
            $data['rain']['1h'] ?? 0,
            $data['visibility'],
            $data['dt']
        );
    }
}
