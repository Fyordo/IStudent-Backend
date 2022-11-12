<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $code
 * @property string $title
 */
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

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'direction_id', 'id');
    }
}
