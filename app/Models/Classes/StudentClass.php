<?php

namespace App\Models\Classes;

use App\Models\Group;
use App\Models\Notification;

class StudentClass
{
    public int $id;
    public string $name;
    public string $email;
    public string $token;
    public int $groupId;
    public bool $isHeadman;
    public array $notifications;

    public function __construct($arr){
        $this->id = $arr["id"];
        $this->name = $arr["name"];
        $this->email = $arr["email"];
        $this->token = $arr["token"] ?? "";
        $this->groupId = $arr["groupId"];
        $this->isHeadman = $arr["isHeadman"];
        $this->photo = $arr["photo"];
        $this->notifications = [];

        $notesDB = Notification::where("studentId", $this->id)->orderBy('date')->get();

        foreach ($notesDB as $note)
        {
            array_push($this->notifications, new NotificationClass($note));
        }
    }

    public function getGroup() : GroupClass
    {
        return new GroupClass(Group::where("id", $this->groupId)->first());
    }

    public function printGroup(): string
    {
        $group = $this->getGroup();
        return $group->groupCourse . '.' . $group->groupNumber;
    }

    public static function getStudent($arr): ?StudentClass
    {
        if ($arr == null){
            return null;
        }

        return new StudentClass($arr);
    }
}
