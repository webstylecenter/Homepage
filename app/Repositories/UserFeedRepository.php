<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Interfaces\RepositoryInterface;
use App\Models\Note;
use Illuminate\Database\Eloquent\Model;

class UserFeedRepository extends AbstractRepository implements RepositoryInterface
{
    /**
     * @var Note $model
     */
    protected $model;

    public function __construct(UserFeed $model)
    {
        parent::__construct($model);
    }
}
