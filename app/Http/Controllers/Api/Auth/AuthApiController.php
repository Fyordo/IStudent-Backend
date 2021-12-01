<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Classes\StudentClass;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthApiController extends Controller
{
    /**
     * Добавление информации
     */
    public function add(Request $request){
        if ($request->isMethod('post')) {
            $token = $request->header("token");
            if ($token == "")
            {
                $array = [
                    'error' => 'Ошибка доступа'
                ];
                return response()->json($array, 405);
            }


            if (!(Student::where("token", $token)->exists())){
                $array = [
                    'error' => 'Ошибка доступа, не найден токен'
                ];
                return response()->json($array,405);
            }


            $access = Student::where("token", $token)->first();
            $studentID = $request->input('studentID');
            if ($studentID != $access['id']){
                $array = [
                    'error' => 'Ошибка доступа'
                ];
                return response()->json($array,405);
            }

            $groupID = $request->input('groupID');
            $password = $request->input('password');
            $isHeadman = $request->input('isHeadman');
            $group = Group::where("id", $groupID)->first();
            if (isset($group)) {
                if (!(isset($group['headmanId']) && $isHeadman)) {
                    Student::where('id', $studentID)->update([
                        'groupId' => (int)$groupID,
                        'password' => $password,
                        'isHeadman' => (bool)$isHeadman
                    ]);
                    if ($isHeadman){
                        Group::where('id', $groupID)->update([
                            'headmanId' => (int)$studentID
                        ]);
                    }
                    $array = [
                        'message' => 'Данные о студенте успешно обновлены'
                    ];
                    return response()->json($array);
                }
                else {
                    $array = [
                        'error' => 'Ошибка, у группы уже есть староста'
                    ];
                    return response()->json($array, 405);
                }
            }
            else {
                $array = [
                    'error' => 'Ошибка, такой группы не существует'
                ];
                return response()->json($array, 405);
            }
        }
        else {
            $array = [
                'error' => 'Ошибка, поддерживается только POST-метод'
            ];
            return response()->json($array, 405);
        }
    }
     /**
     * Авторизация - получение токена
     */
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
                return response()->json($array,405);
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
                return response()->json($array, 401);
            }
        }
        else
        {
            $array = [
                'error' => 'Ошибка, не введены данные'
            ];
            return response()->json($array, 405);
        }
    }
    /**
     * Деавторизация - удаление токена
     */
    public function logout(Request $request)
    {
        if ($request->isMethod('post')) {
            $token = $request->header("token");
            if ($token == "") {
                $array = [
                    'error' => 'Ошибка доступа'
                ];
                return response()->json($array, 405);
            }
            Student::where("token", $token)->update([
                'token' => ''
            ]);
            $array = [
                'message' => 'Токен был удален'
            ];
            return response()->json($array);
        }
        else {
            $array = [
                'error' => 'Ошибка, поддерживается только POST-метод'
            ];
            return response()->json($array, 405);
        }
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
