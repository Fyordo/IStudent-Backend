<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    /**
     * Таблица, связанная с моделью.
     *
     * @var string
     */
    protected $table = 'lessons';
    public $timestamps = false;

    public int $id;
    public string $title;
    public int $lessonNumber;
    public int $weekDay;
    public string $location;
    public string $lecturer;
    public int $groupId;
    public bool $upWeek;
}
