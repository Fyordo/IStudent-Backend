<?php

namespace App\Http\Controllers;

use App\Models\Classes\GroupClass;
use App\Models\Classes\StudentClass;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index($id)
    {
        $group = $this->findGroupById($id);
        $headman = $this->findStudentById($group->headman_id);

        return view("group.index")->with([
            'student' => StudentClass::getStudent(Auth::user()),
            'group' => $group,
            'headman' => $headman
        ]);
    }

    public function all()
    {
        $groupsDB = Group::orderBy('group_course')->orderBy('group_number')->get();
        $groups = [];

        foreach ($groupsDB as $group)
        {
            if ($group['id'] != 0)
                array_push($groups, new GroupClass($group));
        }

        return view("group.all")->with([
            'student' => StudentClass::getStudent(Auth::user()),
            'groups' => $groups,
        ]);
    }

    // ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ

    private function findStudentById($id) : ?StudentClass
    {
        $student = Student::where("id", $id)->first();
        return $student == null ? null : new StudentClass($student);
    }

    private function findGroupById($id) : ?GroupClass
    {
        $group = Group::where("id", $id)->first();
        return $group == null ? null : new GroupClass($group);
    }
}
