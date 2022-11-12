<?php

namespace App\Exceptions;

class ModelDeleteException extends \Exception
{
    public function __construct($model)
    {
        parent::__construct("Model " . $model . " not deleted!");
    }
}
