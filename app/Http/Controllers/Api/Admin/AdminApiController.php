<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Student;
use App\Models\Teacher;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class AdminApiController
{
    private function isAdmin(Request $request) : bool
    {
        $token = $request->header("token");
        if ($token == "")
        {
            return false;
        }

        $admin = Student::where("token", $token)->first();
        if (isset($admin)){
            if ($admin["email"] == "flagutin@sfedu.ru"){
                return true;
            }

            return false;
        }

        return false;
    }

    public function addTeachers(Request $request){
        if ($this->isAdmin($request)){
            $client = new Client();
            $res = json_decode($client->get('http://schedule.sfedu.ru/APIv0/teacher/list')->getBody());
            foreach ($res as $teacher){
                $newTeacher = new Teacher();
                $newTeacher["id"] = $teacher->id;
                $newTeacher["name"] = $teacher->name;
                $newTeacher["degree"] = $teacher->degree;
                $newTeacher->save();
            }

            $array = [
                'status' => 'Перподаватели успешно записаны в базу данных'
            ];
            return response()->json($array);
        }
        $array = [
            'error' => 'Доступ к этим действиям имеет только администратор'
        ];
        return response()->json($array);
    }
}
