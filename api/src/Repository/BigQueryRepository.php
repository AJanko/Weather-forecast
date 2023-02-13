<?php

namespace App\Repository;

use App\Service\BigQuery;

class BigQueryRepository
{

    private BigQuery $client;
    private string   $modelId;

    public function __construct(BigQuery $client, string $modelId)
    {
        $this->client  = $client;
        $this->modelId = $modelId;
    }

    public function getWeatherPrediction(array $currentWeather): bool
    {

        $airTemp       = $currentWeather['temperature_air_2m_f'];
        $windChillTemp = $currentWeather['temperature_windchill_2m_f'];
        $heatIndexTemp = $currentWeather['temperature_heatindex_2m_f'];
        $gpkgHumidity  = $currentWeather['humidity_specific_2m_gpkg'];
        $pctHumidity   = $currentWeather['humidity_relative_2m_pct'];
        $windSpeed     = $currentWeather['wind_speed_10m_mph'];
        $cloudCover    = $currentWeather['cloud_cover_pct'];

        $query = <<<ENDSQL
SELECT * FROM ML.PREDICT(
   MODEL `$this->modelId`,
   (
       SELECT $airTemp AS temperature_air_2m_f,
       $windChillTemp AS temperature_windchill_2m_f,
       $heatIndexTemp AS temperature_heatindex_2m_f,
       $gpkgHumidity AS humidity_specific_2m_gpkg,
       $pctHumidity AS humidity_relative_2m_pct,
       $windSpeed AS wind_speed_10m_mph,
       $cloudCover AS cloud_cover_pct,
   )
);
ENDSQL;

        $result = $this->client->query($query);

        // rewrite later
        return (bool)(float)$result->info()['rows'][0]['f'][0]['v'];
    }
}
