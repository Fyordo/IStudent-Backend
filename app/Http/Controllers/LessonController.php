<?php

namespace App\Http\Controllers;

use App\Models\Classes\GroupClass;
use App\Models\Classes\LessonClass;
use App\Models\Classes\StudentClass;
use App\Models\Group;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function list($groupId, $day, $month, $year)
    {
        $yesterday = date(mktime(0, 0, 0, $month, $day-1, $year));
        $today = mktime(0, 0, 0, $month, $day, $year);
        $tomorrow = date(mktime(0, 0, 0, $month, $day+1, $year));
        $weekDay = date('w', $today);

        $lessonsDB = Lesson::where("week_day", $weekDay)->where('group_id', $groupId)->where('up_week', (int)date('W', $today) % 2 != 0)->orderBy('lesson_number')->get();
        $lessons = [];

        foreach ($lessonsDB as $lesson)
        {
            $lessons[] = new LessonClass($lesson);
        }

        $groupNum = (new GroupClass(Group::where('id', $groupId)->first()))->printGroup();

        return view("lesson.list", [
            'lessons' => $lessons,
            'groupId' => $groupId,
            'yesterday' => $yesterday,
            'today' => $today,
            'tomorrow' => $tomorrow,
            'weekDay' => $this->getNameOfDay($weekDay),
            'groupNum' => $groupNum,
            'upWeek' => (int)date('W', $today) % 2 != 0
        ])->with([
            'student' => StudentClass::getStudent(Auth::user())
        ]);
    }

    public function full($groupId)
    {
        $lessonsDB = Lesson::where('group_id', $groupId)->orderBy('week_day')->orderBy('lesson_number')->get();
        $lessons = [];

        foreach ($lessonsDB as $lesson)
        {
            array_push($lessons, new LessonClass($lesson));
        }

        $weekDays = [
            1 => "Понедельник",
            2 => "Вторник",
            3 => "Среда",
            4 => "Четверг",
            5 => "Пятница",
            6 => "Суббота",
            0 => "Воскресенье",
        ];

        return view("lesson.full", [
            'lessons' => $lessons,
            'groupString' => $this->printGroupById($groupId),
            'weekDays' => $weekDays,
        ])->with([
            'student' => StudentClass::getStudent(Auth::user())
        ]);
    }

    // Вспомогательные функции

    private function getNameOfDay($weekDay) : string
    {
        switch ($weekDay)
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

    private function printGroupById($id) : string
    {
        $group = Group::where("id", $id)->first();
        return $group == null ? "" : $group['group_course'] . '.' . $group['group_number'];
    }
}
