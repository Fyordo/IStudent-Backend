<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $table = "teachers";
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'degree',
        'photo'
    ];

    protected $visible = [
        'id',
        'name',
        'degree',
        'photo'
    ];
}
