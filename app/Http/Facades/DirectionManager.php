<?php

namespace App\Http\Facades;

use App\Http\Services\DirectionService;
use App\Models\Direction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @see DirectionService
 *
 * @method static Direction create(Direction $direction)
 * @method static Direction edit(Direction $direction, array $attributes)
 * @method static Direction delete(Direction $direction)
 * @method static Direction findById(int $id)
 * @method static Collection all()
 */
class DirectionManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return self::class;
    }
}
