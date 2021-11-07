<?php

namespace App\Models\Classes;

use DateTime;

class LessonAddictionClass
{
    public int $id;
    public int $lessonId;
    public DateTime $date;
    public string $description;

    public function __construct($arr)
    {
        $this->id = $arr["id"];
        $this->lessonId = $arr["lessonId"];
        $this->date = $arr["date"];
        $this->description = $arr["description"];
    }
}
