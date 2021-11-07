<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /**
     * Таблица, связанная с моделью.
     *
     * @var string
     */
    protected $table = 'notifications';
    public $timestamps = false;

    public int $id;
    public int $studentId;
    public string $topic;
    public DateTime $date;
    public string $text;


}
