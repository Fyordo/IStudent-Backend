<?php

namespace App\Models\Classes;

use DateTime;

class LessonAddictionClass
{
    public int $id;
    public int $lesson_id;
    public DateTime $date;
    public string $description;

    public function __construct($arr)
    {
        $this->id = $arr["id"];
        $this->lessonId = $arr["lesson_id"];
        $this->date = $arr["date"];
        $this->description = $arr["description"];
    }
}
