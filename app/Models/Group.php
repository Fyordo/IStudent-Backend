<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /**
     * Таблица, связанная с моделью.
     *
     * @var string
     */
    protected $table = 'groups';
    public $timestamps = false;

    public int $id;
    public int $groupNumber;
    public int $groupCourse;
    public int $headmanId;
    public int $directionId;
}
