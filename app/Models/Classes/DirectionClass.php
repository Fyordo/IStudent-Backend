<?php

namespace App\Models\Classes;

use App\Models\Direction;

class DirectionClass
{
    public int $id;
    public string $code;
    public string $title;

    public function __construct($arr){
        $this->id = $arr['id'];
        $this->code = $arr['code'];
        $this->title = $arr['title'];
    }

    /**
     * Получить направление по ID
     * */
    public static function findById(int $id){
        return new DirectionClass(Direction::where('id', $id)->first());
    }
}
