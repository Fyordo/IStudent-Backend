<?php

namespace App\Http\Services;

use App\Http\Repositories\DirectionRepository;
use App\Models\Direction;

class DirectionService
{
    private DirectionRepository $directionRepository;

    public function __construct(DirectionRepository $directionRepository){
        $this->directionRepository = $directionRepository;
    }

    /**
     * @param Direction $direction
     * @return Direction
     * @throws \App\Exceptions\ModelSaveException
     */
    public function create(Direction $direction): Direction
    {
        return $this->directionRepository->save($direction);
    }

    /**
     * @param Direction $direction
     * @return Direction
     * @throws \App\Exceptions\ModelSaveException
     */
    public function edit(Direction $direction, array $attributes): Direction
    {
        foreach ($direction->getFillable() as $field) {
            $direction->$field = $attributes[$field] ?? $direction->$field;
        }
        return $this->directionRepository->save($direction);
    }

    /**
     * @param Direction $direction
     * @return Direction
     * @throws \App\Exceptions\ModelDeleteException
     */
    public function delete(Direction $direction): Direction
    {
        return $this->directionRepository->delete($direction);
    }

    /**
     * @param int $id
     * @return Direction
     * @throws \App\Exceptions\ModelNotFoundException
     */
    public function findById(int $id): Direction
    {
        return $this->directionRepository->findById($id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->directionRepository->all();
    }
}
