<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Checklist;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Models\Note;
use Illuminate\Database\Eloquent\Model;

class ChecklistItemRepository extends AbstractRepository implements RepositoryInterface
{
    /**
     * @var Note $model
     */
    protected $model;

    public function __construct(Checklist $model)
    {
        parent::__construct($model);
    }
}
