<?php

namespace App\Models\Classes;

use DateTime;

class NotificationClass
{
    public int $id;
    public int $studentId;
    public string $topic;
    public $date; // Тут не пишу тип данных, ибо это офигеть можно
    public string $text;

    public function __construct($arr)
    {
        $this->id = $arr['id'];
        $this->studentId = $arr['studentId'];
        $this->topic = $arr['topic'];
        $this->date = date("d.m.Y H:i:s", strtotime($arr['date']));
        $this->text = $arr['text'];
    }
}
