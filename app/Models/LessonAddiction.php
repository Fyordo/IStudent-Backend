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

    protected $fillable = [
        'id',
        'group_id',
        'date',
        'description'
    ];

    protected $visible = [
        'id',
        'group_id',
        'date',
        'description'
    ];
}
