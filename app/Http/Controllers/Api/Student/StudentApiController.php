<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Classes\StudentClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function replace_photo(Request $request)
    {
        if ($request->isMethod('post')) {
            $token = $request->header('token');
            if ($token == "") {
                $array = [
                    'error' => 'Ошибка доступа'
                ];
                return response()->json($array, 405);
            }

            $photo = $request->input('photo');
            $access = Student::where("token", $token)->first();
            $studentID = $request->input('student_id');
            if ($studentID != $access['id']) {
                $array = [
                    'error' => 'Ошибка доступа'
                ];
                return response()->json($array, 405);
            }

            if (isset($access)) {
                Student::where('token', $token)->update([
                    'photo' => $photo
                ]);
                $array = [
                    'message' => 'Данные о студенте успешно обновлены'
                ];
                return response()->json($array);
            }
            else {
                $array = [
                    'error' => 'Ошибка доступа или неверный токен'
                ];
                return response()->json($array, 405);
            }
        }
        else {
            $array = [
                'error' => 'Ошибка, поддерживается только POST-метод'
            ];
        }
        return response()->json($array, 405);
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
