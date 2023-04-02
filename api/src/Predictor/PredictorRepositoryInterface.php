<?php

namespace App\Predictor;

use App\Entity\ModelEntityInterface;

interface PredictorRepositoryInterface
{
    /**
     * Return value depends on the model
     *
     * @return float|bool
     */
    public function predict(ModelEntityInterface $currentData);
}
