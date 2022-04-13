<?php

namespace App\Models\Classes;

use App\Models\Lesson;
use App\Models\LessonAddiction;
use App\Models\Teacher;
use DateTime;
use Illuminate\Support\Facades\Date;

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

    public function __construct($arr, $date = null)
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

        $addictions = LessonAddiction::where('group_id', $arr["group_id"])->get();

        foreach ($addictions as $addiction) {
            $addiction_date = new DateTime($addiction->date);
            $week_day = date('w', $addiction_date);
            $up_week = (int)date('W', $addiction_date) % 2 != env('UP_WEEK');

            if ($arr["up_week"] == $up_week && $arr["week_day"] == $week_day){
                if ($date){
                    if ($date == $addiction_date){
                        $this->addictions[] = new LessonAddiction($addiction);
                    }
                }
                else{
                    $this->addictions[] = new LessonAddiction($addiction);
                }
            }
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
