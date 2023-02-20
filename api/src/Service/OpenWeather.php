<?php

namespace App\Service;

use App\Entity\WeatherData;
use GuzzleHttp\Client;

class OpenWeather
{
    private const URI = "https://api.openweathermap.org/data/2.5/weather?lat=%s&lon=%s&appid=%s";

    private Client $client;
    private string $apiKey;

    public function __construct(Client $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    public function getCurrentWeather(string $lat, string $lon): WeatherData
    {
        $request = $this->client->request(
            'GET',
            sprintf(self::URI, $lat, $lon, $this->apiKey)
        );

        return $this->createWeatherDataInstance(json_decode($request->getBody()->getContents(), true));
    }

    private function createWeatherDataInstance(array $data): WeatherData
    {
        return new WeatherData(
            $data['main']['temp'],
            $data['main']['feels_like'],
            $data['main']['relative_humidity'],
            $data['clouds']['all'],
            $data['wind']['speed'],
            $data['wind']['gust'],
            $data['rain']['1h'] ?? 0,
            $data['visibility']
        );
    }
}
