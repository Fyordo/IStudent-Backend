<?php

namespace App\Models\Classes;

use App\Models\Student;

class GroupClass
{
    public int $id;
    public int $groupNumber;
    public int $groupCourse;
    public int $headmanId;
    public DirectionClass $direction;

    public function __construct($arr){
        $this->id = $arr['id'];
        $this->groupNumber = $arr['groupNumber'];
        $this->groupCourse = $arr['groupCourse'];
        $this->headmanId = $arr['headmanId'] ?? -1;
        $this->direction = DirectionClass::findById($arr['directionId']);
    }

    public function hasHeadman() : bool
    {
        return $this->headmanId != -1;
    }

    public function printGroup(): string
    {
        return $this->groupCourse . '.' . $this->groupNumber;
    }

    public function getStudents() : array
    {
        $arr = [];
        $students = Student::where("groupId", $this->id)->orderBy('name')->get();

        foreach ($students as $student)
        {
            array_push($arr, new StudentClass($student));
        }

        return $arr;
    }

    public function countStudents() : int
    {
        $students = Student::where("groupId", $this->id)->get();

        return count($students);
    }
}
