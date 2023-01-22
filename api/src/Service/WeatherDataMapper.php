<?php

namespace App\Service;

class WeatherDataMapper
{
    // It will map weather data structure sourced from open weather API to structure required by prediction model
    static public function map(array $openWeatherData): array
    {
        return [
            'temperature_air_2m_f' => $openWeatherData['main']['temp'],
            'temperature_windchill_2m_f' => $openWeatherData['wind']['deg'],
            'temperature_heatindex_2m_f' =>$openWeatherData['main']['feels_like'] ,
            'humidity_specific_2m_gpkg' => $openWeatherData['main']['humidity'],
            'humidity_relative_2m_pct' => $openWeatherData['main']['humidity'],
            'wind_speed_10m_mph' => $openWeatherData['wind']['speed'],
            'cloud_cover_pct' => $openWeatherData['clouds']['all'],
        ];
    }
}
