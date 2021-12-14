<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $table = "grades";
    public $timestamps = false;

    protected $fillable = [
        'id',
        'degree'
    ];

    protected $visible = [
        'id',
        'degree'
    ];
}
