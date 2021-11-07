<?php

namespace App\Models\Classes;

use App\Models\LessonAddiction;

class LessonClass
{
    public int $id;
    public string $title;
    public int $lessonNumber;
    public int $weekDay;
    public string $location;
    public string $lecturer;
    public int $groupId;
    public bool $upWeek;
    public array $addictions;

    public function __construct($arr)
    {
        $this->id = $arr["id"];
        $this->title = $arr["title"];
        $this->lessonNumber = $arr["lessonNumber"];
        $this->weekDay = $arr["weekDay"];
        $this->location = $arr["location"];
        $this->lecturer = $arr["lecturer"];
        $this->groupId = $arr["groupId"];
        $this->upWeek = $arr["upWeek"];
        $this->addictions = [];

        $addDB = LessonAddiction::where("lessonId", $this->id)->get();

        foreach ($addDB as $add) {
            array_push($this->addictions, new LessonAddictionClass($add));
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

        return date("H:i", $startTime[$this->lessonNumber - 1]) . " - " . date("H:i", $endTime[$this->lessonNumber - 1]);
    }

    public function getNameOfDay($weekDay = -1) : string
    {
        switch ($this->weekDay)
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
