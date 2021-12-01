<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Direction;
use App\Models\Grade;
use App\Models\Group;
use App\Models\Lesson;
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
            if ($admin["id"] == 1){
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

    public function addGroups(Request $request){
        if ($this->isAdmin($request)){
            $client = new Client();

            $grades = Grade::orderBy('id')->get();

            foreach ($grades as $grade){
                $grade_id = $grade["id"];
                if ($grade_id == 0){
                    continue;
                }
                $directions = Direction::all();
                $groupsAPI = json_decode($client->get('http://schedule.sfedu.ru/APIv0/group/list/' . $grade_id)->getBody());

                foreach ($groupsAPI as $groupAPI){
                    $group = new Group();
                    $group["id"] = $groupAPI->id;
                    $group["groupNumber"] = $groupAPI->num;
                    $group["groupCourse"] = $groupAPI->gradeid;
                    $group["headmanId"] = null;
                    $group["directionId"] = 1;
                    foreach ($directions as $direction){
                        if ($direction["shortName"] == $groupAPI->name){
                            $group["directionId"] = $direction["id"];
                        }
                    }

                    $group->save();
                }
            }

            $array = [
                'status' => 'Группы успешно записаны в базу данных'
            ];
            return response()->json($array);
        }
        $array = [
            'error' => 'Доступ к этим действиям имеет только администратор'
        ];
        return response()->json($array);
    }

    public function addSchedule(Request $request){
        ini_set('max_execution_time', 60*10);
        if ($this->isAdmin($request)){
            $client = new Client();
            $groups = Group::orderBy('id')->get();

            foreach ($groups as $group){
                if ($group['id'] == 0){
                    continue;
                }

                $res = json_decode($client->get('http://schedule.sfedu.ru/APIv0/schedule/group/' . $group['id'])->getBody());

                $lessonsAPI = $res->lessons;
                $curriculaAPI = $res->curricula;

                foreach ($lessonsAPI as $les){
                    $lessonAPI = $this->getLessonById($les->id, $curriculaAPI);

                    $lesson = new Lesson();
                    $lesson["title"] = $lessonAPI->subjectname;
                    $lesson["groupId"] = $group['id'];
                    $lesson["lecturer"] = $lessonAPI->teachername;
                    $lesson["lessonNumber"] = $this->getLessonNumber($les);
                    $lesson["location"] = $lessonAPI->roomname;
                    $lesson["weekDay"] = $this->getLessonWeekDay($les);
                    $type = $this->getLessonWeekType($les);

                    if ($type == "f"){
                        $lesson["upWeek"] = true;
                        $lesson->save();

                        $lesson = new Lesson();
                        $lesson["title"] = $lessonAPI->subjectname;
                        $lesson["groupId"] = $group['id'];
                        $lesson["lecturer"] = $lessonAPI->teachername;
                        $lesson["lessonNumber"] = $this->getLessonNumber($les);
                        $lesson["location"] = $lessonAPI->roomname;
                        $lesson["weekDay"] = $this->getLessonWeekDay($les);
                        $lesson["upWeek"] = false;
                        $lesson->save();
                    }
                    else{
                        $lesson["upWeek"] = $type == 'u';
                        $lesson->save();
                    }

                }
            }

            $array = [
                'status' => 'Расписание успешно записано в базу данных'
            ];
            return response()->json($array);
        }
        $array = [
            'error' => 'Доступ к этим действиям имеет только администратор'
        ];
        return response()->json($array);
    }

    private function getLessonById($id, $lessons){
        foreach ($lessons as $lesson) {
            if ($lesson->lessonid == $id){
                return $lesson;
            }
        }
        return null;
    }

    private function getLessonNumber($lesson){
        $slot = $lesson->timeslot;
        $time = substr($slot, 3, 5);
        switch ($time){
            case "08:00":
                return 1;
            case "09:50":
                return 2;
            case "11:55":
                return 3;
            case "13:45":
                return 4;
            case "15:50":
                return 5;
        }
        return 1;
    }

    private function getLessonWeekDay($lesson){
        $slot = $lesson->timeslot;
        $day = (int)substr($slot, 1, 1);

        return $day + 1;
    }

    private function getLessonWeekType($lesson){
        $slot = $lesson->timeslot;
        $type = substr($slot, 21);

        return $type[0];
    }
}
