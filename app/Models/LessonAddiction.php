<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonAddiction extends Model
{
    /**
     * Таблица, связанная с моделью.
     *
     * @var string
     */
    protected $table = 'lesson_addictions';
    public $timestamps = false;

    public int $id;
    public int $lessonId;
    public DateTime $date;
    public string $description;
}
