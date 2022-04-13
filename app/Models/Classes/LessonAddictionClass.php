<?php

namespace App\Models\Classes;

use App\Models\Lesson;
use DateTime;

class LessonAddictionClass
{
    public int $id;
    public DateTime $date;
    public string $description;
    public int $group_id;

    public function __construct($arr)
    {
        $this->id = $arr["id"];
        $this->lessonId = $arr["group_id"];
        $this->date = $arr["date"];
        $this->description = $arr["description"];
    }
}
