<?php
declare(strict_types=1);

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface RepositoryInterface
{
    public function get(int $id);
    public function all(?array $columns = ['*']):Collection;
    public function delete(int $id);
    public function getWith(int $id, ?array $relations = []);
}
