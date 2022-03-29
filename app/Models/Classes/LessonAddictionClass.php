<?php

namespace App\Models\Classes;

use App\Models\Lesson;
use DateTime;

class LessonAddictionClass
{
    public int $id;
//    public int $lesson_id;
    public DateTime $date;
    public string $description;
    public LessonClass $lesson;

    public function __construct($arr)
    {
        $this->id = $arr["id"];
//        $this->lessonId = $arr["lesson_id"];
        $this->lesson = Lesson::where('id',$arr["lesson_id"])->first();
        $this->date = $arr["date"];
        $this->description = $arr["description"];
    }
}
