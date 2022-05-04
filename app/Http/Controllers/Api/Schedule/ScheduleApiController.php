<?php

namespace App\Http\Controllers\Api\Schedule;

use App\Http\Controllers\Controller;
use App\Models\Classes\GroupClass;
use App\Models\Classes\LessonClass;
use App\Models\Group;
use App\Models\Lesson;
use App\Models\LessonAddiction;
use App\Models\Student;
use DateTime;
use Illuminate\Http\Request;

class ScheduleApiController extends Controller
{
    public function day(Request $request, $group_id)
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
            $day = $request->input("day");
            $month = $request->input("month");
            $year = $request->input("year");

            $today = mktime(0, 0, 0, $month, $day, $year);
            $weekDay = date('w', $today);

            $lessonsDB = Lesson::where("week_day", $weekDay)->where('group_id', $group_id)->where('up_week', (int)date('W', $today) % 2 == env("UP_WEEK"))->orderBy('lesson_number')->get();
            $lessons = [];

            foreach ($lessonsDB as $lesson)
            {
                $lessons[] = new LessonClass($lesson, true, $day, $month, $year);
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
            $type = $request->input("type");

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

                if ($type == "up"){
                    $lessonsDB = Lesson::where('group_id', $group_id)->where('week_day', $i)->where("up_week", true)->orderBy('lesson_number')->get();
                }
                else if ($type == "down"){
                    $lessonsDB = Lesson::where('group_id', $group_id)->where('week_day', $i)->where("up_week", false)->orderBy('lesson_number')->get();
                }
                else{
                    $lessonsDB = Lesson::where('group_id', $group_id)->where('week_day', $i)->orderBy('lesson_number')->get();
                }

                $lessonsDay = [];
                foreach ($lessonsDB as $lesson)
                {
                    $lessonsDay[] = new LessonClass($lesson);
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

    public function week(Request $request)
    {
        return response()->json([
            'type' => (int)date('W') % 2 == env("UP_WEEK") ? "up" : "down"
        ]);
    }


    // MY

    public function MYupdateLessonAddictions(Request $request){
        if ($request->isMethod('post')) {
            $token = $request->header("token");
            if ($token == "")
            {
                $array = [
                    'error' => 'Ошибка доступа'
                ];
                return response()->json($array, 405);
            }

            $access = Student::where("token", $token)->first();
            if (isset($access)) {
                if ($access->is_headman) {
                    $hour_minutes = $this->getLessonTimeByNumber($request->input('lesson_number'));
                    $date = mktime(
                        $hour_minutes['hour'],
                        $hour_minutes['minutes'],
                        0,
                        $request->input('month'),
                        $request->input('day'),
                        $request->input('year'));
                    $dt = new DateTime();
                    $dt->setTimestamp($date);
                    LessonAddiction::insert([
                        [
                            'group_id' => $access->group_id,
                            'date' => $dt,
                            'description' => $request->input('text')
                        ]
                    ]);
                    $array = [
                        'status' => 'Дополнение успешно сохранено'
                    ];
                }
                else {
                    $array = [
                        'status' => 'Вы не староста'
                    ];
                }
            }
            else {
                $array = [
                    'status' => 'Неверный токен'
                ];
            }
        }
        else {
            $array = [
                'status' => 'Ошибка, поддерживается только POST-метод'
            ];
        }

        return response()->json($array);
    }

    public function MYday(Request $request)
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
            $day = $request->input("day");
            $month = $request->input("month");
            $year = $request->input("year");

            $today = mktime(0, 0, 0, $month, $day, $year);
            $weekDay = date('w', $today);

            $lessonsDB = Lesson::where("week_day", $weekDay)->where('group_id', $access->group_id)->where('up_week', $this->upWeek($today))->orderBy('lesson_number')->get();
            $lessons = [];

            foreach ($lessonsDB as $lesson)
            {
                $lessons[] = new LessonClass($lesson, true, $day, $month, $year);
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

    public function MYfull(Request $request)
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
            $type = $request->input("type");

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
                if ($type == "up"){
                    $lessonsDB = Lesson::where('group_id', $access->group_id)->where('week_day', $i)->where("up_week", true)->orderBy('lesson_number')->get();
                }
                else if ($type == "down"){
                    $lessonsDB = Lesson::where('group_id', $access->group_id)->where('week_day', $i)->where("up_week", false)->orderBy('lesson_number')->get();
                }
                else{
                    $lessonsDB = Lesson::where('group_id', $access->group_id)->where('week_day', $i)->orderBy('lesson_number')->get();
                }


                $lessonsDay = [];
                foreach ($lessonsDB as $lesson)
                {
                    $lessonsDay[] = new LessonClass($lesson);
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

    // РАБОТАЕТ С БОЖЬЕЙ ПОМОЩЬЮ, НЕ ТРОГАТЬ!!!
    public function MYall(Request $request)
    {
        $token = $request->header("token");
        if ($token == "")
        {
            $array = [
                'error' => 'Ошибка доступа'
            ];
            return response()->json($array, 405);
        }

        $student = Student::where("token", $token)->first();
        if (isset($student))
        {
            $month = $request->input("month");
            $year = $request->input("year");

            if ($month < 8){ // Если второй семестр
                $start_datetime = mktime(0, 0, 0, 1, 1, $year);
                $end_datetime = mktime(0, 0, 0, 8, 1, $year);
            }
            else{ // Если первый семестр
                $start_datetime = mktime(0, 0, 0, 8, 1, $year);
                $end_datetime = mktime(0, 0, 0, 1, 1, $year+1);
            }

            $list = array_unique(array_column(Lesson::where('group_id', $student->group_id)
                ->select('title')->get()->toArray(), 'title'));
            sort($list);
            $lessons = [];

            foreach ($list as $item){
                $lessons[$item] = [];
            }

            for ($i = $start_datetime; $i < $end_datetime; $i += 60*60*24){
                $weekDay = date('w', $i);

                $schedule_today = Lesson::where('group_id', $student->group_id)->where('week_day', $weekDay)->where('up_week', $this->upWeek($i))->get()->toArray();
                foreach ($schedule_today as $item){
                    $lesson = new LessonClass($item, true, date('j', $i), date('n', $i), date('Y', $i));
                    $lesson->date = date('d.m.Y', $i);
                    $lessons[$lesson->title][] = $lesson;
                }
            }

            return response()->json([
                'list' => $list,
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

    private function upWeek(int $datetime)
    {
        return (int)date('W', $datetime) % 2 == env("UP_WEEK");
    }

    private function getLessonTimeByNumber(int $lesson_number){
        switch ($lesson_number){
            case 1:
                return [
                    'hour' => 8,
                    'minutes' => 0
                ];
            case 2:
                return [
                    'hour' => 9,
                    'minutes' => 50
                ];
            case 3:
                return [
                    'hour' => 11,
                    'minutes' => 55
                ];
            case 4:
                return [
                    'hour' => 13,
                    'minutes' => 45
                ];
            case 5:
                return [
                    'hour' => 15,
                    'minutes' => 50
                ];
        }
    }
}
