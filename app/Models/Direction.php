<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direction extends Model
{
    /**
     * Таблица, связанная с моделью.
     *
     * @var string
     */
    protected $table = 'directions';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'code',
        'title'
    ];

    protected $visible = [
        'id',
        'code',
        'title'
    ];
}
