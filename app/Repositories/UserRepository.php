<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Interfaces\RepositoryInterface;
use App\User;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends AbstractRepository implements RepositoryInterface
{
    /**
     * @var User $model
     */
    protected $model;

    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
