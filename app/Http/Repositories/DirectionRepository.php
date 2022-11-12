<?php

namespace App\Http\Repositories;

use App\Exceptions\ModelDeleteException;
use App\Exceptions\ModelNotFoundException;
use App\Exceptions\ModelSaveException;
use App\Models\Direction;
use Illuminate\Database\Eloquent\Collection;

class DirectionRepository
{
    /**
     * @param Direction $model
     * @return Direction
     * @throws ModelSaveException
     */
    public function save(Direction $model): Direction
    {
        try {
            $model->save();
        }
        catch (\Exception $exception){
            throw new ModelSaveException(Direction::class);
        }

        return $model;
    }

    /**
     * @param Direction $model
     * @return Direction
     * @throws ModelDeleteException
     */
    public function delete(Direction $model): Direction
    {
        try {
            $model->delete();
        }
        catch (\Exception $exception){
            throw new ModelDeleteException(Direction::class);
        }

        return $model;
    }

    /**
     * @param int $id
     * @return Direction
     * @throws ModelNotFoundException
     */
    public function findById(int $id): Direction
    {
        try {
            $model = Direction::find($id);

            if ($model === null){
                throw new ModelNotFoundException(Direction::class, $id);
            }
        }
        catch (\Exception $exception){
            throw new ModelNotFoundException(Direction::class, $id);
        }

        return $model;
    }

    /**
     * @return Collection
     */
    public function all() : Collection
    {
        return Direction::all();
    }
}
