<?php

namespace App\Repositories;

use Closure;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class AbstractRepository
{
    /**
     * Instance that extends Illuminate\Database\Eloquent\Model
     *
     * @var Builder|Model|Authenticatable
     */
    protected Builder|Model|Authenticatable $model;

    /**
     * AbstractRepository constructor.
     */
    public function __construct()
    {
    }

    /**
     * Get Model instance
     *
     * @return Builder|Model|Authenticatable
     */
    public function getModel(): Builder|Model|Authenticatable
    {
        return $this->model;
    }

    /**
     * @inheritdoc
     */
    public function findOne($id, array $relations = null): ?Model
    {
        $builder = null;

        if (!empty($relations)) {
            $builder = static function (Builder $builder) use ($relations) {
                return $builder->with($relations);
            };
        }

        return $this->findOneBy(['id' => $id], $builder);
    }

    /**
     * @inheritdoc
     */
    public function findOneBy(array $criteria, Closure $builder = null): ?Model
    {
        $queryBuilder = $this->model->where($criteria);

        if (is_callable($builder)) {
            $builder($queryBuilder);
        }

        return $queryBuilder->first();
    }

    /**
     * @inheritdoc
     */
    public function findBy(array $searchCriteria = [], Closure $builder = null, bool $isPaginate = true): Collection|LengthAwarePaginator
    {
        $limit = $searchCriteria['limit'] ?? config('constant.limit', 15); // it's needed for pagination
        $filter = $searchCriteria['filter'] ?? [];
        $sort = $searchCriteria['sort'] ?? '';
        $except = $searchCriteria['except'] ?? [];
        $exceptNull = $searchCriteria['exceptNull'] ?? [];

        $queryBuilder = $this->model->where(function ($query) use ($filter, $except, $exceptNull) {
            $this->applySearchCriteriaInQueryBuilder($query, $filter, $except, $exceptNull);
        });

        $this->applySortingInQueryBuilder($queryBuilder, $sort);

        if (is_callable($builder)) {
            $builder($queryBuilder);
        }

        if ($isPaginate) {
            return $queryBuilder->paginate($limit);
        }

        return $queryBuilder->get();
    }

    /**
     * @inheritdoc
     */
    public function create(array $data): Model|Builder
    {
        return $this->model->create($data);
    }

    /**
     * @inheritdoc
     */
    public function update(Model $model, array $data, string $connection = 'mysql'): ?Model
    {
        $fillAbleProperties = $this->model->getFillable();

        foreach ($data as $key => $value) {
            // update only fillAble properties
            if (in_array($key, $fillAbleProperties, true)) {
                $model->{$key} = $value;
            }
        }

        $model->save();

        // get updated model from database
        if ($connection === 'mongodb') {
            $model = $this->findOneBy($data);
        } else {
            // @phpstan-ignore-next-line
            $model = $this->findOne($model->id);
        }

        return $model;
    }

    /**
     * @inheritdoc
     */
    public function findIn($key, array $values): Collection
    {
        return $this->model->whereIn($key, $values)->get();
    }

    /**
     * @inheritdoc
     */
    public function findNotNull($key): Collection
    {
        return $this->model->whereNotNull($key)->get();
    }

    /**
     * Delete the model from the database.
     *
     * @return bool|null
     *
     * @throws \LogicException
     */
    public function delete(Model $model): ?bool
    {
        return $model->delete();
    }

    /**
     * Get the first record matching the attributes. If the record is not found, create it.
     *
     * @param  array $attributes
     * @param  array $values
     * @return Model
     */
    public function firstOrCreate(array $attributes, array $values): Model
    {
        return $this->model->firstOrCreate($attributes, $values);
    }

    /**
     * Get the first record matching the attributes or instantiate it.
     *
     * @param  array $attributes
     * @param  array $values
     * @return Model
     */
    public function firstOrNew(array $attributes, array $values): Model
    {
        return $this->model->firstOrNew($attributes, $values);
    }

    /**
     * Find a model by its primary key or throw an exception.
     *
     * @param  mixed        $id
     * @param  array|string $columns
     * @return Collection
     *
     * @throws ModelNotFoundException
     */
    public function findOrFail(mixed $id, array|string $columns = ['*']): Model|Collection
    {
        return $this->model->findOrFail($id, $columns);
    }

    /**
     * Create or update a record matching the attributes, and fill it with values.
     *
     * @param  array $attributes
     * @param  array $values
     * @return Model
     */
    public function updateOrCreate(array $attributes, array $values): Model
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

    /**
     * Set model data
     * @param Builder|Model $model
     */
    public function setModel(Builder|Model $model)
    {
        $this->model = $model;
    }

    /**
     * Apply condition on query builder based on search criteria
     */
    protected function applySearchCriteriaInQueryBuilder(object $queryBuilder, array $searchCriteria = [], array $except = [], array $exceptNull = []): object
    {
        foreach ($searchCriteria as $key => $value) {
            // skip pagination related query params and ambiguous fields
            if (in_array($key, ['page', 'limit', 'deleted_at'], true)) {
                continue;
            }

            // we can pass multiple params for a filter with array
            if (is_array($value)) {
                $queryBuilder->whereIn($key, $value);
            } else {
                $operator = '=';

                if (str_contains($value, '%')) {
                    $operator = 'like';
                }

                $orWhere = explode('_or_', $key);

                if (count($orWhere) > 1) {
                    $queryBuilder->where(static function ($query) use ($orWhere, $operator, $value) {
                        foreach ($orWhere as $key) {
                            $query->orWhere($key, $operator, $value);
                        }

                        return $query;
                    });
                } else {
                    $queryBuilder->where($key, $operator, $value);
                }
            }
        }

        foreach ($except as $key => $value) {
            $queryBuilder->whereNotIn($key, $value);
        }

        foreach ($exceptNull as $column) {
            $queryBuilder->whereNotNull($column);
        }

        return $queryBuilder;
    }

    /**
     * Apply condition on query builder based on search criteria
     *
     * @param Builder $queryBuilder
     * @param string  $sortString
     */
    protected function applySortingInQueryBuilder(Builder $queryBuilder, string $sortString = ''): Builder
    {
        $sortFields = explode(',', $sortString);

        foreach ($sortFields as $field) {
            if (empty($field)) {
                continue;
            }

            if (strpos($field, '-') === 0) {
                $field = substr($field, 1);

                if ($field) {
                    $queryBuilder->orderByDesc($field);
                }
            } else {
                $queryBuilder->orderBy($field);
            }
        }

        return $queryBuilder;
    }

    /**
     * Get records with conditions
     *
     * @param string|array|null $selects
     * @param string|array|null $relations
     * @param array             $conditions
     * @param array             $orderBys
     *
     * @return Collection|array
     */
    public function getWithConditions(string|array $selects = null, string|array|null $relations = [], array $conditions = null, array $orderBys = null): Collection|array
    {
        $query = $this->model->with($relations === '' ? [] : $relations);

        if ($selects) {
            $query->select($selects);
        }

        if (is_array($conditions)) {
            $query->where($conditions);
        }

        if (is_array($orderBys)) {
            foreach ($orderBys as $column => $direction) {
                $query->orderBy($column, $direction);
            }
        }

        return $query->get();
    }

    /**
     * Get records with conditions
     *
     * @param string            $id
     * @param string|array|null $selects
     * @param string|array|null $relations
     *
     * @return Collection
     */
    public function getOneWithRelations(string $id, string|array $selects = null, string|array|null $relations = []): Collection
    {
        $query = $this->model->find($id)->with($relations === '' ? [] : $relations);

        if ($selects) {
            $query->select($selects);
        }

        return $query->get();
    }

    /**
     * Paginate the given query.
     *
     * @param  int|null|\Closure    $perPage
     * @return LengthAwarePaginator
     *
     * @throws \InvalidArgumentException
     */
    public function getPaginate(int|null|\Closure $perPage): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }
}
