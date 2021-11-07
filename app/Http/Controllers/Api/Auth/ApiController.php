<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Classes\StudentClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function login(Request $request)
    {
        if ($request->hasHeader("login") && $request->hasHeader("password"))
        {
            $login = $request->header("login");
            $password = $request->header("password");

            $student = Student::where("email", $login)->first();
            if (!isset($student))
            {
                $array = [
                    'error' => 'Ошибка, такого студента нет'
                ];
                return response()->json($array);
            }

            if ($student["password"] == $password)
            {
                $token = $this->generateRandomString(50);
                $student->update([
                    'token' => $token,
                ]);
                return response()->json(["token" => $token]);
            }
            else
            {
                $array = [
                    'error' => 'Ошибка, неверный логин и пароль'
                ];
                return response()->json($array);
            }
        }
        else
        {
            $array = [
                'error' => 'Ошибка, не введены данные'
            ];
            return response()->json($array);
        }
    }

    public function logout()
    {

    }

    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
