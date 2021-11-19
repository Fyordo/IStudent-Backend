<?php

namespace App\Models;

use App\Http\Middleware\Authenticate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    /**
     * Таблица, связанная с моделью.
     *
     * @var string
     */
    protected $table = 'students';
    public $timestamps = false;

    private int $id;
    public string $name;
    public string $email;
    private string $password;
    public string $token;
    public int $groupId;
    public bool $isHeadman;
    public string $photo;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'groupId',
        'token',
        'isHeadman',
        'photo'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'token',
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [

    ];
}
