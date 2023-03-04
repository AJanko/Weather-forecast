<?php

namespace App\Controller;

use App\Service\Predictor;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;

class WeatherController extends AbstractFOSRestController
{
    const WILL_RAIN = "There will be rain today";
    const WONT_RAIN = "No rain for today :)";

    private Predictor $predictor;

    public function __construct(Predictor $predictor)
    {
        $this->predictor = $predictor;
    }

    /** @Rest\Get("/predict/{lat}/{lon}", name="predict_weather") */
    public function getPrediction(string $lat, string $lon): JsonResponse
    {
        return $this->json($this->predictor->predict($lat, $lon) ? self::WILL_RAIN : self::WONT_RAIN);
    }
}
