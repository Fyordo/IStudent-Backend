<?php

namespace App\Http\Controllers\Api\Group;

use App\Http\Controllers\Controller;
use App\Models\Classes\GroupClass;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Http\Request;

class GroupApiController extends Controller
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
            $groupDB = Group::where("id", $id)->first();

            if (isset($groupDB))
            {
                $group = new GroupClass($groupDB);
                return response()->json($group);
            }
            else
            {
                $array = [
                    'error' => 'Такой группы нет'
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

    public function getStudents(Request $request, int $id)
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
            $groupDB = Group::where("id", $id)->first();

            if (isset($groupDB))
            {
                $group = new GroupClass($groupDB);
                return response()->json($group->getStudents());
            }
            else
            {
                $array = [
                    'error' => 'Такой группы нет'
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

    public function all(Request $request)
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
            $groupsIds = Group::all();
            $groups = [];

            foreach ($groupsIds as $gr)
            {
                array_push($groups, new GroupClass(Group::where('id', $gr["id"])->first()));
            }
            return response()->json($groups);
        }
        else
        {
            $array = [
                'error' => 'Ошибка доступа или неверный токен'
            ];
            return response()->json($array,405);
        }
    }
}
