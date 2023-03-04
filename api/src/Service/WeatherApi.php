<?php

namespace App\Service;

use GuzzleHttp\Client;

class WeatherApi
{
    private Client $client;
    private string $apiKey;

    public function __construct(Client $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    public function requestData(string $url): array
    {
        $request = $this->client->request(
            'GET',
            "$url&key=$this->apiKey"
        );

        return json_decode($request->getBody()->getContents(), true);
    }
}
