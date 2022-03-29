<?php

namespace App\Models\Classes;

use App\Models\LessonAddiction;
use App\Models\Teacher;

class LessonClass
{
    public int $id;
    public string $title;
    public int $lesson_number;
    public int $week_day;
    public string $location;
    public ?TeacherClass $teacher;
    public int $group_id;
    public bool $up_week;
    public array $addictions;

    public function __construct($arr)
    {
        $this->id = $arr["id"];
        $this->title = $arr["title"];
        $this->lesson_number = $arr["lesson_number"];
        $this->week_day = $arr["week_day"];
        $this->location = $arr["location"];
        $this->teacher = $arr["teacher_id"] == "" ? null : new TeacherClass(Teacher::where('id', $arr["teacher_id"])->first()->toArray());
        $this->group_id = $arr["group_id"];
        $this->up_week = $arr["up_week"];
        $this->addictions = [];

        $addDB = LessonAddiction::where("lesson_id", $this->id)->get();

        foreach ($addDB as $add) {
            $this->addictions[] = $add;
        }
    }

    public function getTime() : string
    {
        $startTime = [
            mktime(8,0),
            mktime(9,50),
            mktime(11,55),
            mktime(13,45),
            mktime(15,50)
        ];

        $endTime = [
            mktime(9,35),
            mktime(11,25),
            mktime(13,30),
            mktime(15,20),
            mktime(17,25)
        ];

        return date("H:i", $startTime[$this->lesson_number - 1]) . " - " . date("H:i", $endTime[$this->lesson_number - 1]);
    }

    public function getNameOfDay($week_day = -1) : string
    {
        switch ($this->week_day)
        {
            case 1:
                return "Понедельник";
            case 2:
                return "Вторник";
            case 3:
                return "Среда";
            case 4:
                return "Четверг";
            case 5:
                return "Пятница";
            case 6:
                return "Суббота";
            default:
                return "Воскресенье";
        }
    }
}
