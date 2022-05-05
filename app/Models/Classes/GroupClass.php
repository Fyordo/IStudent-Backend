<?php

namespace App\Models\Classes;

use App\Models\Student;

class GroupClass
{
    public int $id;
    public int $group_number;
    public string $group_course;
    public int $headman_id;
    public DirectionClass $direction;

    public function __construct($arr){
        $this->id = $arr['id'];
        $this->group_number = $arr['group_number'];
        $this->group_course = $arr['group_course'];
        $this->headman_id = $arr['headman_id'] ?? -1;
        $this->direction = DirectionClass::findById($arr['direction_id']);
    }

    public function hasHeadman() : bool
    {
        return $this->headman_id != -1;
    }

    public function printGroup(): string
    {
        return $this->group_course . '.' . $this->group_number;
    }

    public function getStudents() : array
    {
        $arr = [];
        $students = Student::where("group_id", $this->id)->orderBy('name')->get();

        foreach ($students as $student)
        {
            $arr[] = new StudentClass($student);
        }

        return $arr;
    }

    public function countStudents() : int
    {
        $students = Student::where("group_id", $this->id)->get();

        return count($students);
    }
}
