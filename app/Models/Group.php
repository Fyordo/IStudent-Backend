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

    protected $fillable = [
        'id',
        'group_number',
        'group_course',
        'headman_id',
        'direction_id'
    ];

    protected $visible = [
        'id',
        'group_number',
        'group_course',
        'headman_id',
        'direction_id'
    ];
}
