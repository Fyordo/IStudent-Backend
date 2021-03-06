<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentGroup extends Model
{
    use HasFactory;

    protected $table = "student_groups";
    public $timestamps = false;

    protected $fillable = [
        'student',
        'course',
        'group'
    ];

    protected $visible = [
        'student',
        'course',
        'group'
    ];
}
