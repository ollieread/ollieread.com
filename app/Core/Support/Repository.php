<?php

namespace Ollieread\Core\Support;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Repository
 *
 * @package Ollieread\Support
 */
abstract class Repository
{
    use Concerns\HandlesTransactions;

    private $filter;

    public function setFilter($filter)
    {
        $this->filter = $filter;
        return $this;
    }

    public function getFilter()
    {
        return $this->filter;
    }

    protected function filter(Builder $query): void
    {

    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(): Collection
    {
        return $this->query()->get();
    }

    /**
     * @param array $conditions
     * @param mixed ...$select
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function conditionalQuery(array $conditions, ...$select): Builder
    {
        $query  = $this->query($select ?: '*');

        foreach ($conditions as $column => $value) {
            $query->where($column, '=', $value);
        }

        return $query;
    }

    /**
     * @param array $conditions
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function findBy(array $conditions)
    {
        return $this->conditionalQuery($conditions)->get();
    }

    public function findIn(string $column, array $values)
    {
        return $this->query()->whereIn($column, $values)->get();
    }

    /**
     * @param array $conditions
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function findOneBy(array $conditions)
    {
        return $this->conditionalQuery($conditions)->first();
    }

    /**
     * @return string
     */
    abstract protected function model(): string;

    /**
     * @param array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function newModel($attributes = []): Model
    {
        $modelClass = $this->model();
        return new $modelClass($attributes);
    }

    /**
     * @param int|null $perPage
     * @param array    $columns
     * @param string   $pageName
     * @param int|null $page
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(int $perPage = null, array $columns = ['*'], string $pageName = 'page', ?int $page = null): LengthAwarePaginator
    {
        return $this->query()->paginate($perPage, $columns, $pageName, $page);
    }

    /**
     * @param mixed ...$select
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function query(...$select): Builder
    {
        $query = $this->newModel()->newQuery();

        if ($select) {
            $query->select($select);
        }

        if ($this->getFilter()) {
            $this->filter($query);
        }

        return $query;
    }
}