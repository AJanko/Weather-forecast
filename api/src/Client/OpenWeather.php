<?php

namespace App\Client;

use GuzzleHttp\Client;

class OpenWeather
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
            $url . '&appid=' . $this->apiKey
        );

        return json_decode($request->getBody()->getContents(), true);
    }
}
