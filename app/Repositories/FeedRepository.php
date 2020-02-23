<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Feed;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Models\Note;
use Illuminate\Database\Eloquent\Model;

class FeedRepository extends AbstractRepository implements RepositoryInterface
{
    /**
     * @var Note $model
     */
    protected $model;

    public function __construct(Feed $model)
    {
        parent::__construct($model);
    }
}
