<?php

namespace App\Client;

use Phpml\Estimator;
use Phpml\ModelManager;

class PHPML
{
    private string       $pathToModel;
    private ModelManager $modelManager;

    public function __construct(string $pathToModel, ModelManager $modelManager)
    {
        $this->pathToModel  = $pathToModel;
        $this->modelManager = $modelManager;
    }

    public function getModel(): Estimator
    {
        return $this->modelManager->restoreFromFile($this->pathToModel);
    }

    public function saveModel(Estimator $model): void
    {
        $this->modelManager->saveToFile($model, $this->pathToModel);
    }
}
