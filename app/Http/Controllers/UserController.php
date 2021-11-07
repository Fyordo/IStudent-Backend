<?php

namespace App\Http\Controllers;

use App\Models\Classes\GroupClass;
use App\Models\Classes\StudentClass;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function page($id){
        $owner = $this->findStudentById($id);

        return view("user.page")->with([
            'ownerStudent' => $owner,
            'student' => StudentClass::getStudent(Auth::user())
        ]);
    }

    // ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ

    private function findStudentById($id) : ?StudentClass
    {
        $student = Student::where("id", $id)->first();
        return $student == null ? null : new StudentClass($student);
    }


}
