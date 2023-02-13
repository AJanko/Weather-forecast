<?php

namespace App\Entity;

class WeatherData
{
    private float $temperature;
    private float $feelTemperature;
    private float $relativeHumidity;
    private float $cloudCover;
    private float $windSpeed;
    private float $windGust;
    private float $rain;
    private float $visibility;
    private float $dewPoint;

    private bool $willRain;  // prediction for the day after

    public function getTemperature(): float
    {
        return $this->temperature;
    }

    public function setTemperature(float $temperature): void
    {
        $this->temperature = $temperature;
    }

    public function getFeelTemperature(): float
    {
        return $this->feelTemperature;
    }

    public function setFeelTemperature(float $feelTemperature): void
    {
        $this->feelTemperature = $feelTemperature;
    }

    public function getRelativeHumidity(): float
    {
        return $this->relativeHumidity;
    }

    public function setRelativeHumidity(float $relativeHumidity): void
    {
        $this->relativeHumidity = $relativeHumidity;
    }

    public function getCloudCover(): float
    {
        return $this->cloudCover;
    }

    public function setCloudCover(float $cloudCover): void
    {
        $this->cloudCover = $cloudCover;
    }

    public function getWindSpeed(): float
    {
        return $this->windSpeed;
    }

    public function setWindSpeed(float $windSpeed): void
    {
        $this->windSpeed = $windSpeed;
    }

    public function getWindGust(): float
    {
        return $this->windGust;
    }

    public function setWindGust(float $windGust): void
    {
        $this->windGust = $windGust;
    }

    public function getRain(): float
    {
        return $this->rain;
    }

    public function setRain(float $rain): void
    {
        $this->rain = $rain;
    }

    public function getVisibility(): float
    {
        return $this->visibility;
    }

    public function setVisibility(float $visibility): void
    {
        $this->visibility = $visibility;
    }

    public function getDewPoint(): float
    {
        return $this->dewPoint;
    }

    public function setDewPoint(float $dewPoint): void
    {
        $this->dewPoint = $dewPoint;
    }

    public function isWillRain(): bool
    {
        return $this->willRain;
    }

    public function setWillRain(bool $willRain): void
    {
        $this->willRain = $willRain;
    }
}
