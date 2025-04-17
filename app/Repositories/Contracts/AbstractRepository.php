<?php

namespace App\Repositories\Contracts;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Throwable;

abstract class AbstractRepository
{
    public const int NO_LOCK = 0;
    public const int LOCK_FOR_UPDATE = 1;
    public const int LOCK_FOR_SELECT = 2;

    protected Model $model;

    public function __construct()
    {
        $this->setInstance();
    }

    public function get(array $columns = ['*']): Collection
    {
        return $this->getQuery()->get($columns);
    }

    public function find($id, array $columns = ['*']): ?Model
    {
        return $this->getQuery()->find($id, $columns);
    }

    public function findOrFail($id, array $columns = ['*']): Model|ModelNotFoundException
    {
        return $this->getQuery()->findOrFail($id, $columns);
    }

    public function firstOrFail(array $criteria): Model|ModelNotFoundException
    {
        return $this->getQuery()->where($criteria)->firstOrFail();
    }

    public function findAll(array|string $columns = ['*']): Collection
    {
        return $this->getQuery()->get($columns);
    }

    public function first(array|string $columns = ['*']): ?Model
    {
        return $this->getQuery()->first($columns);
    }

    public function findBy(
        array $criteria,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null,
        array|string $columns = ['*']
    ): Collection {
        return $this->getQuery()
            ->where($criteria)
            ->when($orderBy, function ($query, $orderBy) {
                foreach ($orderBy as $column => $direction) {
                    $query->orderBy($column, $direction);
                }

                return $query;
            })
            ->when($limit, fn($query, $limit) => $query->limit($limit))
            ->when($offset, fn($query, $offset) => $query->offset($offset))
            ->get($columns);
    }

    public function findOneBy(
        array $criteria,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null,
        array|string $columns = ['*']
    ): ?Model {
        return $this->getQuery()
            ->where($criteria)
            ->when($orderBy, function ($query, $orderBy) {
                foreach ($orderBy as $column => $direction) {
                    $query->orderBy($column, $direction);
                }

                return $query;
            })
            ->when($limit, fn($query, $limit) => $query->limit($limit))
            ->when($offset, fn($query, $offset) => $query->offset($offset))
            ->first($columns);
    }

    public function firstOrCreate(array $attributes, ?array $values = null): ?Model
    {
        if ($values) {
            return $this->getQuery()->firstOrCreate($attributes, $values);
        }

        return $this->getQuery()->firstOrCreate($attributes);
    }

    public function updateOrCreate(array $attributes, array $values): ?Model
    {
        return $this->getQuery()->updateOrCreate($attributes, $values);
    }

    public function persist(array $attributes): Model
    {
        return tap($this->instance($attributes), fn(Model $instance) => $instance->save());
    }

    /**
     * @throws Throwable
     */
    public function update($id, array $attributes): Model
    {
        $model = $this->getQuery()->findOrFail($id);

        return $this->updateModel($model, $attributes);
    }

    /**
     * @throws Throwable
     */
    public function updateModel(Model $model, array $attributes): Model
    {
        return tap($this->checkInstance($model), fn(Model $model) => $model->fill($attributes)->save());
    }

    /**
     * @throws Throwable
     */
    public function forceUpdateModel(Model $model, array $attributes): Model
    {
        $this->checkInstance($model);

        $model->forceFill($attributes)->save();

        return $model;
    }

    /**
     * @throws Throwable
     */
    public function increment(Model $model, string $column, float|int $amount = 1): int
    {
        return $this->checkInstance($model)->increment($column, $amount);
    }

    /**
     * @throws Throwable
     */
    public function decrement(Model $model, string $column, float|int $amount = 1): int
    {
        return $this->checkInstance($model)->decrement($column, $amount);
    }

    public function create(array $attributes = []): Model
    {
        return $this->getQuery()->create($attributes);
    }

    public function delete($id): bool|null
    {
        $model = $this->getQuery()->findOrFail($id);

        return $this->deleteModel($model);
    }

    /**
     * @throws Throwable
     */
    public function deleteModel(Model $model): bool|null
    {
        return $this->checkInstance($model)->delete();
    }

    public function orderBy($column, $direction = 'asc'): self
    {
        $this->model = $this->model->orderBy($column, $direction);

        return $this;
    }

    /**
     * @throws Throwable
     */
    public function transactional(callable $callable)
    {
        try {
            DB::beginTransaction();
            $result = $callable($this);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }

        return $result;
    }

    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->model, $method], $arguments);
    }

    /**
     *
     * @throws ModelNotFoundException
     */
    public function findWithRelation(
        int|string $id,
        array $with,
        array $columns = ['*'],
        bool $trowFailException = false
    ): ?Model {
        $builder = $this->model->with($with);

        return !$trowFailException ? $builder->find($id, $columns) : $builder->findOrFail($id, $columns);
    }

    public function count(array $criteria, array|string $columns = 'id'): int
    {
        return $this->getQuery()->select($columns)->where($criteria)->count();
    }

    protected function setInstance(array $attributes = []): Model
    {
        $this->model = $this->instance($attributes);

        return $this->model;
    }

    protected function getQuery(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * @throws Throwable
     */
    protected function checkInstance(Model $model): Model
    {
        if (!$model instanceof $this->model) {
            throw new Exception("The `$model` is not instance of $this->model.");
        }

        return $model;
    }

    abstract protected function instance(array $attributes = []): Model;
}
