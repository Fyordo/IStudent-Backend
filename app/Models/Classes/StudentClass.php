<?php

namespace App\Models\Classes;

use App\Models\Group;
use App\Models\Notification;

class StudentClass
{
    public int $id;
    public string $name;
    public string $email;
    //public int $group_id;
    public GroupClass $group;
    public bool $is_headman;
    public string $photo;
    public array $notifications;

    public function __construct($arr){
        $this->id = $arr["id"];
        $this->name = $arr["name"];
        $this->email = $arr["email"];
        //$this->group_id = $arr["group_id"];
        $this->group = new GroupClass(Group::where('id', $arr["group_id"])->first()->toArray());
        $this->is_headman = $arr["is_headman"];
        $this->photo = $arr["photo"];
        $this->notifications = [];

        $notesDB = Notification::where("student_id", $this->id)->orderBy('date')->get();

        foreach ($notesDB as $note)
        {
            array_push($this->notifications, new NotificationClass($note));
        }
    }

    public function getGroup() : GroupClass
    {
        return new GroupClass(Group::where("id", $this->group->id)->first());
    }

    public function printGroup(): string
    {
        $group = $this->getGroup();
        return $group->group_course . '.' . $group->group_number;
    }

    public static function getStudent($arr): ?StudentClass
    {
        if ($arr == null){
            return null;
        }

        return new StudentClass($arr);
    }
}
