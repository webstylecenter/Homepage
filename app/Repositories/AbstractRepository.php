<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\AbstractModel;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository implements RepositoryInterface
{
    protected $model;
    protected AbstractRepository $repository;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function create(array $data, ?array $relations = null): int
    {
        $relationDataCache = [];

        if ($relations) {
            foreach ($relations as $relation => $relationData) {
                $relationDataCache[$relation] = $relationData;
                unset($data[$relation]);
            }
        }

        $modelId = $this->model->insertGetId($data);

        if (count($relationDataCache) > 0) {
            $model = $this->model->find($modelId);

            foreach ($relationDataCache as $relation => $relationData) {
                $this->updateRelations($model, $relation, $relationData);
            }
        }

        return $this->model->insertGetId($data);
    }

    public function persist(?int $id, array $data, ?array $relations = null): int
    {
        if ($id === null || $id === 0) {
            return $this->create($data, $relations);
        }

        return $this->update($id, $data, $relations);

    }

    public function all(?array $columns = ['*']): Collection
    {
        return $this->model->all($columns);
    }

    /**
     * @param int $id
     * @return bool|int
     */
    public function delete(int $id): int
    {
        return $this->model->destroy($id);
    }

    /**
     * @param int $id
     * @param array $data
     * @return int
     */
    public function update(int $id, array $data, ?array $relations = null): int
    {
        $this->model->find($id)->update($data);

        $model = $this->model->find($id);
        if ($relations) {
            foreach ($relations as $relation => $relationData) {
                $this->updateRelations($model, $relation, $relationData);
                unset($data[$relation]);
            }
        }

        return $id;
    }

    /**
     * @return mixed
     */
    protected function getTableName(): string
    {
        return $this->model->getTable();
    }

    /**
     * @param int $id
     * @param array|null $relations
     * @return AbstractModel
     */
    public function getWith(int $id, ?array $relations = []): AbstractModel
    {
        return $this->model->with($relations)->find(['id' => $id])->first();
    }

    /**
     * @param int $id
     * @return AbstractModel
     */
    public function get(int $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * @param string $key
     * @param $value
     * @return Collection
     */
    public function where(string $key, $value): Collection
    {
        return $this->model->where($key, $value)->get();
    }

    /**
     * @param Model $model
     * @param string $relation
     * @param array $ids
     * @return Model
     */
    protected function updateRelations(Model $model, string $relation, ?array $ids): Model
    {
        if ($ids === null) {
            return $model;
        }

        $model->$relation()->detach();
        $model->$relation()->attach($ids);
        return $model;
    }
}
