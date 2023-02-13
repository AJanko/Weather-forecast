<?php

namespace App\Repository;

use App\Entity\WeatherData;
use App\Service\BigQuery;

class BigQueryRepository
{

    private BigQuery $client;
    private string   $projectId;
    private string   $modelId;
    private string   $trainingTableId;

    public function __construct(BigQuery $client, string $projectId, string $modelId, string $trainingTableId)
    {
        $this->client          = $client;
        $this->modelId         = $modelId;
        $this->trainingTableId = $trainingTableId;
        $this->projectId       = $projectId;
    }

    public function getWeatherPrediction(array $currentWeather): bool
    {
        $modelId = $this->getStructureId($this->modelId);

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

        $result = $this->client->query($query);

        // rewrite later
        return (bool)(float)$result->info()['rows'][0]['f'][0]['v'];
    }

    /** @param array<int, WeatherData> $data */
    public function uploadTrainingData(array $data): void
    {
        $trainingTableId = $this->getStructureId($this->trainingTableId);

        $values = implode(
            ",",
            array_map(function (WeatherData $weatherData) {
                return "(" . implode(
                        ",",
                        [
                            $weatherData->getTemperature(),
                            $weatherData->getFeelTemperature(),
                            $weatherData->getRelativeHumidity(),
                            $weatherData->getCloudCover(),
                            $weatherData->getWindSpeed(),
                            $weatherData->getWindGust(),
                            $weatherData->getRain(),
                            $weatherData->getVisibility(),
                            $weatherData->getDewPoint(),
                            $weatherData->isWillRain(),
                            rand(),
                        ]
                    ) . ")";
            }, $data)
        );

        $sql = <<<SQL
INSERT INTO `$trainingTableId` (
    temperature,
    feel_temperature,
    relative_humidity,
    cloud_cover,
    wind_speed,
    wind_gust,
    rain,
    visibility,
    dew_point,
    will_rain,
    split_col
) VALUES %s
SQL;

        $query = sprintf($sql, $values);

        $this->client->query($query);
    }

    private function getStructureId(string $structureId): string
    {
        return "$this->projectId.$structureId";
    }
}
