<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Classes\StudentClass;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentApiController extends Controller
{
    public function get(Request $request, int $id)
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
            $studentDB = Student::where("id", $id)->first();

            if (isset($studentDB))
            {
                $student = StudentClass::getStudent($studentDB);
                return response()->json($student);
            }
            else
            {
                $array = [
                    'error' => 'Такого студента нет'
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
            $studentDB = Student::where("token", $token)->first();

            if (isset($studentDB))
            {
                $student = StudentClass::getStudent($studentDB);
                return response()->json($student);
            }
            else
            {
                $array = [
                    'error' => 'Такого студента нет'
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
}
