<?php

namespace App\Service;

use Google\Cloud\BigQuery\BigQueryClient;

class BigQuery
{
    private const MODEL_ID = 'weather.forecast_model';

    /**
     * @var BigQueryClient
     */
    private $client;

    public function __construct(BigQueryClient $client)
    {
        $this->client = $client;
    }

    public function getWeatherPrediction(array $currentWeather)
    {
        $modelId = self::MODEL_ID;

        $airTemp       = $currentWeather['temperature_air_2m_f'];
        $windChillTemp = $currentWeather['temperature_windchill_2m_f'];
        $heatIndexTemp = $currentWeather['temperature_heatindex_2m_f'];
        $gpkgHumidity  = $currentWeather['humidity_specific_2m_gpkg'];
        $pctHumidity   = $currentWeather['humidity_relative_2m_pct'];
        $windSpeed     = $currentWeather['wind_speed_10m_mph'];
        $cloudCover    = $currentWeather['cloud_cover_pct'];

        $query = <<<ENDSQL
SELECT * FROM ML.PREDICT(
   MODEL `$modelId`,
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

        $queryJobConfig = $this->client->query($query);
        $queryResults   = $this->client->runQuery($queryJobConfig);

        // rewrite later
        return (bool)(float)$queryResults->info()['rows'][0]['f'][0]['v'];
    }
}
