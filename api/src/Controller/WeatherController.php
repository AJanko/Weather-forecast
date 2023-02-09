<?php

namespace App\Controller;

use App\Service\Predictor;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class WeatherController extends AbstractFOSRestController
{
    /**
     * @var Predictor
     */
    private $predictor;

    public function __construct(Predictor $predictor)
    {
        $this->predictor = $predictor;
    }

    /** @Rest\Get("/predict/{lat}/{lon}", name="predict_weather") */
    public function getPrediction(string $lat, string $lon)
    {
        $data = ['prediction' => $this->predictor->predict($lat, $lon)];

        return $this->json($data);
    }
}
