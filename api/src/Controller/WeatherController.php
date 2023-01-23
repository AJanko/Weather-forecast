<?php

namespace App\Controller;

use App\Service\Predictor;
use FOS\RestBundle\Controller\AbstractFOSRestController;

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

    public function getPrediction()
    {
        $data = ['prediction' => $this->getPrediction()];
        $view = $this->view($data, 200);

        return $this->handleView($view);
    }
}
