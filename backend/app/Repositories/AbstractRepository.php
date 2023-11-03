<?php

namespace App\Repositories;

use Closure;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    /**
     * Name of the Model with absolute namespace
     *
     * @var string
     */
    protected $modelName;

    /**
     * Instance that extends Illuminate\Database\Eloquent\Model
     *
     * @var Builder|Model
     */
    protected $model;

    /**
     * AbstractRepository constructor.
     */
    public function __construct()
    {
    }

    /**
     * Get Model instance
     *
     * @return Builder|Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * {@inheritdoc}
     */
    public function findOne($id, array $relations = null)
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
     * {@inheritdoc}
     */
    public function findOneBy(array $criteria, Closure $builder = null)
    {
        $queryBuilder = $this->model->where($criteria);

        if (is_callable($builder)) {
            $builder($queryBuilder);
        }
        return $queryBuilder->first();
    }

    /**
     * {@inheritdoc}
     */
    public function findBy(array $searchCriteria = [], Closure $builder = null, $paginate = true)
    {
        $limit = $searchCriteria['limit'] ?? 15; // it's needed for pagination
        $filter = $searchCriteria['filter'] ?? [];
        $sort = $searchCriteria['sort'] ?? null;
        $except = $searchCriteria['except'] ?? [];
        $exceptNull = $searchCriteria['exceptNull'] ?? [];

        $queryBuilder = $this->model->where(function ($query) use ($filter, $sort, $except, $exceptNull) {
            $this->applySearchCriteriaInQueryBuilder($query, $filter, $except, $exceptNull);
        });

        $this->applySortingInQueryBuilder($queryBuilder, $sort);

        if (is_callable($builder)) {
            $builder($queryBuilder);
        }

        if ($paginate) {
            return $queryBuilder->paginate($limit);
        }

        return $queryBuilder->get();
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $data): Model|Builder
    {
        return $this->model->create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(Model $model, array $data, string $connection = 'mysql')
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
            $model = $this->findOne($model->id);
        }

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function findIn($key, array $values)
    {
        return $this->model->whereIn($key, $values)->get();
    }

    /**
     * {@inheritdoc}
     */
    public function findNotNull($key)
    {
        return $this->model->whereNotNull($key)->get();
    }

    /**
     * @throws Exception
     */
    public function delete(Model $model): ?bool
    {
        return $model->delete();
    }

    public function firstOrCreate(array $data = []): Model|Builder
    {
        return $this->model->firstOrCreate($data);
    }

    public function firstOrNew(array $data = []): Model|Builder
    {
        return $this->model->firstOrNew($data);
    }

    /**
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function findOrFail(int $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Update or create model
     *
     * @return array
     */
    public function updateOrCreate(array $key, array $data)
    {
        return $this->model->updateOrCreate($key, $data);
    }

    /**
     * Set model data
     */
    public function setModel(Model $model)
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
     * @param null $sortString
     */
    protected function applySortingInQueryBuilder(Builder $queryBuilder, $sortString = ''): Builder
    {
        $sortFields = explode(',', $sortString);

        if (count($sortFields) > 0) {
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
        }

        return $queryBuilder;
    }
}
