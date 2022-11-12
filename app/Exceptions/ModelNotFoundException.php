<?php

namespace App\Exceptions;

class ModelNotFoundException extends \Exception
{
    public function __construct($model, int $id)
    {
        parent::__construct("Model " . $model . " with id=" . $id . " not found!");
    }
}
