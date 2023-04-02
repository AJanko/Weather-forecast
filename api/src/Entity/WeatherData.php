<?php

namespace App\Entity;

class WeatherData implements ModelEntityInterface
{
    private float $temperature;
    private float $feelTemperature;
    private float $relativeHumidity;
    private float $cloudCover;
    private float $windSpeed;
    private float $windGust;
    private float $rain;
    private float $visibility;
    private int   $timestamp;

    private ?bool $willRain;  // prediction for the day after
    private ?float $rainAvgDayAfter; // prediction for next 24hours

    public function __construct(
        float $temperature,
        float $feelTemperature,
        float $relativeHumidity,
        float $cloudCover,
        float $windSpeed,
        float $windGust,
        float $rain,
        float $visibility,
        int $timestamp,
        ?bool $willRain = null,
        ?float $rainAvgDayAfter = null
    ) {
        $this->temperature      = $temperature;
        $this->feelTemperature  = $feelTemperature;
        $this->relativeHumidity = $relativeHumidity;
        $this->cloudCover       = $cloudCover;
        $this->windSpeed        = $windSpeed;
        $this->windGust         = $windGust;
        $this->rain             = $rain;
        $this->visibility       = $visibility;
        $this->timestamp        = $timestamp;
        $this->willRain         = $willRain;
        $this->rainAvgDayAfter  = $rainAvgDayAfter;
    }

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

    public function isWillRain(): bool
    {
        return $this->willRain;
    }

    public function setWillRain(bool $willRain): void
    {
        $this->willRain = $willRain;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    public function setTimestamp(int $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    public function getRainAvgDayAfter(): ?float
    {
        return $this->rainAvgDayAfter;
    }

    public function setRainAvgDayAfter(?float $rainAvgDayAfter): void
    {
        $this->rainAvgDayAfter = $rainAvgDayAfter;
    }

    public function toArray(): array
    {
        return [
            'temperature'      => $this->getTemperature(),
            'feelTemperature'  => $this->getFeelTemperature(),
            'relativeHumidity' => $this->getRelativeHumidity(),
            'cloudCover'       => $this->getCloudCover(),
            'windSpeed'        => $this->getWindSpeed(),
            'windGust'         => $this->getWindGust(),
            'rain'             => $this->getRain(),
            'visibility'       => $this->getVisibility(),
            'timestamp'        => $this->getTimestamp(),
            'willRain'         => $this->isWillRain(),
            'rainAvgDayAfter'  => $this->getRainAvgDayAfter(),
        ];
    }

    public static function fromArray(array $data): WeatherData
    {
        return new self(
            $data['temperature'],
            $data['feelTemperature'],
            $data['relativeHumidity'],
            $data['cloudCover'],
            $data['windSpeed'],
            $data['windGust'],
            $data['rain'],
            $data['visibility'],
            $data['timestamp'],
            $data['willRain'],
            $data['rainAvgDayAfter']
        );
    }

    public function getSamplesArray(): array
    {
        return [
            $this->getTemperature(),
            $this->getFeelTemperature(),
            $this->getRelativeHumidity(),
            $this->getCloudCover(),
            $this->getWindSpeed(),
            $this->getWindGust(),
            $this->getVisibility(),
            $this->getTimestamp(),
            $this->getRain(),
        ];
    }

    public function getTarget(): float
    {
        return $this->getRainAvgDayAfter();
    }
}
