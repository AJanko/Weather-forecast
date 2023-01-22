<?php

namespace App\Service;

use GuzzleHttp\Client;

class OpenWeather
{
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
            "https://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&appid=$this->apiKey"
        );

        return json_decode($request->getBody()->getContents(), true);
    }
}
