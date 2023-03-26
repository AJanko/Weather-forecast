<?php

namespace App\Controller;

use App\Service\Predictor;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;

class WeatherController extends AbstractFOSRestController
{
    const RAIN_MSG = "Today there will be: %smm of rain";

    private Predictor $predictor;

    public function __construct(Predictor $predictor)
    {
        $this->predictor = $predictor;
    }

    /** @Rest\Get("/predict/{lat}/{lon}", name="predict_weather") */
    public function getPrediction(string $lat, string $lon): JsonResponse
    {
        $rain = $this->predictor->predict($lat, $lon);

        return $this->json(sprintf(self::RAIN_MSG, $rain));
    }
}
