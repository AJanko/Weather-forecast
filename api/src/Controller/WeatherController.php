<?php

namespace App\Controller;

use App\Service\Predictor;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

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

    /** @Rest\Get("/predict", name="predict_weather") */
    public function getPrediction(Request $request)
    {
        $data = ['prediction' => $this->predictor->predict()];
        $view = $this->view($data, 200);

        return $this->handleView($view);
    }
}
