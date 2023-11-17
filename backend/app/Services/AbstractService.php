<?php

namespace App\Services;

use App\Repositories\AbstractRepository;
use Closure;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractService
{
    protected AbstractRepository $repository;

    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param int $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getPaginate(int $perPage): LengthAwarePaginator
    {
        return $this->repository->getPaginate($perPage);
    }

    /**
     * Create record data
     *
     * @param array $data
     *
     * @return Model|Builder
     */
    public function create(array $data): Model|Builder
    {
        return $this->repository->create($data);
    }

    /**
     * Get data record by id
     *
     * @param int $id
     *
     * @return Model|Builder
     */
    public function show(int $id): Model|Builder
    {
        return $this->repository->findOne($id);
    }

    /**
     * Update data record by model
     *
     * @param ?Model $model
     * @param array  $data
     *
     * @return Model|Builder
     */
    public function update(?Model $model, array $data): Model|Builder
    {
        return $this->repository->update($model, $data);
    }

    /**
     * Delete data record by model
     *
     * @param ?Model $model
     *
     * @return bool
     */
    public function delete(?Model $model): bool
    {
        return $this->repository->delete($model);
    }

    /**
     * Get multiple records by conditions search
     *
     * @param array   $searchCriteria
     * @param Closure $builder
     * @param bool    $isPaginate
     *
     * @return Collection|LengthAwarePaginator
     */
    public function findBy(array $searchCriteria = [], Closure $builder = null, bool $isPaginate = true): Collection|LengthAwarePaginator
    {
        return $this->repository->findBy($searchCriteria, $builder, $isPaginate);
    }

    /**
     * Get one record by conditions search
     *
     * @param array   $criteria
     * @param Closure $builder
     *
     * @return Model|null
     */
    public function findOneBy(array $criteria, Closure $builder = null): ?Model
    {
        return $this->repository->findOneBy($criteria, $builder);
    }

    /**
     * Update or create a record
     *
     * @param array $key
     * @param array $data
     *
     * @return Model|null
     */
    public function updateOrCreate(array $key, array $data): ?Model
    {
        return $this->repository->updateOrCreate($key, $data);
    }
}
