<?php

namespace App\Exceptions;

class ModelSaveException extends \Exception
{
    public function __construct($model)
    {
        parent::__construct("Model " . $model . " not saved!");
    }
}
