<?php

namespace App\Http\Controllers\Api\Schedule;

use App\Http\Controllers\Controller;
use App\Models\Classes\GroupClass;
use App\Models\Classes\LessonClass;
use App\Models\Group;
use App\Models\Lesson;
use App\Models\Student;
use Illuminate\Http\Request;

class ScheduleApiController extends Controller
{
    public function day(Request $request, $group_id, $day, $month, $year)
    {
        $token = $request->header("token");
        if ($token == "")
        {
            $array = [
                'error' => 'Ошибка доступа'
            ];
            return response()->json($array, 405);
        }

        $access = Student::where("token", $token)->first();
        if (isset($access))
        {
            $today = mktime(0, 0, 0, $month, $day, $year);
            $weekDay = date('w', $today);

            $lessonsDB = Lesson::where("weekDay", $weekDay)->where('groupId', $group_id)->where('upWeek', (int)date('W', $today) % 2 != 0)->orderBy('lessonNumber')->get();
            $lessons = [];

            foreach ($lessonsDB as $lesson)
            {
                array_push($lessons, new LessonClass($lesson));
            }

            return response()->json([
                'lessons' => $lessons
            ]);

        }
        else
        {
            $array = [
                'error' => 'Ошибка доступа или неверный токен'
            ];
            return response()->json($array, 405);
        }
    }

    public function full(Request $request, $group_id)
    {
        $token = $request->header("token");
        if ($token == "")
        {
            $array = [
                'error' => 'Ошибка доступа'
            ];
            return response()->json($array, 405);
        }

        $access = Student::where("token", $token)->first();
        if (isset($access))
        {
            $lessons = [];

            $weekDays = [
                1 => "MON",
                2 => "TUE",
                3 => "WED",
                4 => "THU",
                5 => "FRI",
                6 => "SAT",
                0 => "SUN",
            ];

            for ($i = 0; $i <= 6; $i++){
                $lessonsDB = Lesson::where('groupId', $group_id)->where('weekDay', $i)->orderBy('lessonNumber')->get();

                $lessonsDay = [];
                foreach ($lessonsDB as $lesson)
                {
                    array_push($lessonsDay, new LessonClass($lesson));
                }

                $lessons += [$weekDays[$i] => $lessonsDay];
            }

            return response()->json([
                'lessons' => $lessons
            ]);

        }
        else
        {
            $array = [
                'error' => 'Ошибка доступа или неверный токен'
            ];
            return response()->json($array, 405);
        }
    }
}
