<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Classes\StudentClass;
use App\Models\Classes\TeacherClass;
use App\Models\Lesson;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherApiController extends Controller
{
    public function get(Request $request, int $teacher_id)
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
            $teacherDB = Teacher::where("id", $teacher_id)->first();

            if (isset($teacherDB))
            {
                $student = new TeacherClass($teacherDB);
                return response()->json($student);
            }
            else
            {
                $array = [
                    'error' => 'Такого преподавателя нет'
                ];
                return response()->json($array, 405);
            }

        }
        else
        {
            $array = [
                'error' => 'Ошибка доступа или неверный токен'
            ];
            return response()->json($array, 405);
        }
    }

    public function all(Request $request){
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
            return Teacher::all()->toArray();
        }
        else
        {
            $array = [
                'error' => 'Ошибка доступа или неверный токен'
            ];
            return response()->json($array, 405);
        }
    }

    public function MYget(Request $request)
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
            $teacher_ids = Lesson::where('group_id', $access->group_id)->select('teacher_id')->distinct()->get()->toArray();

            $teachers = [];

            foreach ($teacher_ids as $teacher_id) {
                $teacher_id = $teacher_id['teacher_id'];

                if ($teacher_id) {
                    $teachers[] = new TeacherClass(Teacher::where('id', $teacher_id)->first());
                }

            }

            return response()->json($teachers);
        }
        else
        {
            $array = [
                'error' => 'Ошибка доступа или неверный токен'
            ];
            return response()->json($array, 405);
        }

    }

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

        $access = Student::where("token", $token)->first();
        if (isset($access))
        {
            $list = [];
            foreach (Teacher::all() as $teacher){
                $list[] = new TeacherClass($teacher->toArray());
            }
            return response()->json($list);
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
