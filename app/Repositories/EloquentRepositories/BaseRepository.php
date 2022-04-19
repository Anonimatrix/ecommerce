<?php

namespace App\Repositories\EloquentRepositories;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected $model;
    protected $relations;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        $query = $this->model;

        if ($this->relations && count($this->relations) > 0) {
            $query->with($this->relations);
        }

        return $query->get();
    }

    public function paginate(int $quantity)
    {
        return $this->model->paginate($quantity);
    }

    public function getById(int $id)
    {
        $instance = $this->model->find($id);

        if ($instance === null) {
            throw new ModelNotFoundException();
        }

        return $instance;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, int $id)
    {
        $instance = $this->getById($id);

        return $instance->update($data);
    }

    public function delete(int $id)
    {
        $instance = $this->getById($id);

        return $instance->delete();
    }

    public function forceDelete(int $id)
    {
        $instance = $this->getById($id);

        return  $instance->forceDelete();
    }
}
