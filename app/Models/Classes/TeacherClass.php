<?php

namespace App\Models\Classes;

class TeacherClass
{
    public int $id;
    public string $name;
    public string $degree;
    public string $photo;

    public function __construct($arr)
    {
        $this->id = $arr["id"];
        $this->name = $arr["name"];
        $this->degree = $arr["degree"];
        $this->photo = $arr["photo"];
    }
}
