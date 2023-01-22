<?php

namespace App\Service;

use Google\Cloud\BigQuery\BigQueryClient;

class BigQuery
{
    /**
     * @var BigQueryClient
     */
    private $client;

    public function __construct(BigQueryClient $client)
    {
        $this->client = $client;
    }

    public function testBigQuery()
    {
        $query          = <<<ENDSQL
SELECT
  CONCAT(
    'https://stackoverflow.com/questions/',
    CAST(id as STRING)) as url,
  view_count
FROM `bigquery-public-data.stackoverflow.posts_questions`
WHERE tags like '%google-bigquery%'
ORDER BY view_count DESC
LIMIT 10;
ENDSQL;

        $queryJobConfig = $this->client->query($query);
        $queryResults   = $this->client->runQuery($queryJobConfig);
    }

    public function testPredictWeather()
    {
        $query          = <<<ENDSQL
SELECT * FROM ML.PREDICT(
 MODEL `weather.forecast_model`,
   (SELECT 3 AS temperature_air_2m_f,
     10 AS temperature_windchill_2m_f,
     1 AS temperature_heatindex_2m_f,
     2 AS humidity_specific_2m_gpkg,
     3 AS humidity_relative_2m_pct,
     4 AS wind_speed_10m_mph,
     5 AS cloud_cover_pct,
   )
);
ENDSQL;

        $queryJobConfig = $this->client->query($query);
        $queryResults   = $this->client->runQuery($queryJobConfig);

        return $queryResults->info()['rows'][0];
    }

    public function getWeatherPrediction(array $currentWeather) {
        $airTemp       = $currentWeather['temperature_air_2m_f'];
        $windChillTemp = $currentWeather['temperature_windchill_2m_f'];
        $heatIndexTemp = $currentWeather['temperature_heatindex_2m_f'];
        $gpkgHumidity  = $currentWeather['humidity_specific_2m_gpkg'];
        $pctHumidity   = $currentWeather['humidity_relative_2m_pct'];
        $windSpeed     = $currentWeather['wind_speed_10m_mph'];
        $cloudCover    = $currentWeather['cloud_cover_pct'];

        $query          = <<<ENDSQL
SELECT * FROM ML.PREDICT(
 MODEL `weather.forecast_model`,
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

        return (bool)(float)$queryResults->info()['rows'][0]['f'][0]['v'];
    }
}
