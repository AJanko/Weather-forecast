<?php

namespace App\Service;

use GuzzleHttp\Client;

class OpenWeather
{
    private const URI = "https://api.openweathermap.org/data/2.5/weather?lat=%s&lon=%s&appid=%s";

    /** @var Client */
    private $client;
    /** @var string */
    private $apiKey;

    public function __construct(Client $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    public function getCurrentWeather(string $lat, string $lon)
    {
        $request = $this->client->request(
            'GET',
            sprintf(self::URI, $lat, $lon, $this->apiKey)
        );

        return json_decode($request->getBody()->getContents(), true);
    }
}
