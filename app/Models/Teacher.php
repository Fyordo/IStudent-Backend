<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $table = "teachers";
    public $timestamps = false;

    public int $id;
    public string $name;
    public string $degree;
    public string $photo;
}
