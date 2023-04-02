<?php

namespace App\Repository\DataWarehouse;

use App\Client\BigQuery;
use App\Entity\WeatherData;
use App\Predictor\PredictorRepositoryInterface;

class BigQueryRepository implements PredictorRepositoryInterface
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

    public function predict(WeatherData $currentWeather): bool
    {
        $modelId = $this->getStructureId($this->modelId);

        $temp       = $currentWeather->getTemperature();
        $feelTemp   = $currentWeather->getFeelTemperature();
        $humid      = $currentWeather->getRelativeHumidity();
        $clouds     = $currentWeather->getCloudCover();
        $windSpeed  = $currentWeather->getWindSpeed();
        $windGust   = $currentWeather->getWindGust();
        $rain       = $currentWeather->getRain();
        $visibility = $currentWeather->getVisibility();

        $query = <<<ENDSQL
SELECT * FROM ML.PREDICT(
   MODEL `$modelId`,
   (
        SELECT $temp AS temperature,
        $feelTemp AS feel_temperature,
        $humid AS relative_humidity,
        $clouds AS cloud_cover,
        $windSpeed AS wind_speed,
        $windGust AS wind_gust,
        $rain AS rain,
        $visibility AS visibility,
   )
);
ENDSQL;

        $result = $this->client->query($query);

        return filter_var($result->info()['rows'][0]['f'][0]['v'], FILTER_VALIDATE_BOOLEAN);
    }

    /** @param array<int, WeatherData> $data */
    public function uploadTrainingData(array $data): void
    {
        // training data for new model
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
                            $weatherData->getTimestamp(),
                            (int)$weatherData->isWillRain(),
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
    timestamp,
    will_rain,
    split_col
) VALUES %s;
SQL;

        $query = sprintf($sql, $values);

        $this->client->query($query);
    }

    private function getStructureId(string $structureId): string
    {
        return "$this->projectId.$structureId";
    }
}
