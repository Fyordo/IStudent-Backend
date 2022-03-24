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

    protected $fillable = [
        'id',
        'title',
        'lesson_number',
        'week_day',
        'location',
        'teacher_id',
        'group_id',
        'up_week'
    ];

    protected $visible = [
        'id',
        'title',
        'lesson_number',
        'week_day',
        'location',
        'teacher_id',
        'group_id',
        'up_week'
    ];
}
