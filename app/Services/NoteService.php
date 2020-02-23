<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\ChecklistItemRepository;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\NoteRepository;
use App\Services\Interfaces\AbstractServiceInterface;
use App\User;
use Illuminate\Database\Eloquent\Collection;

class NoteService extends AbstractService implements AbstractServiceInterface
{
    protected RepositoryInterface $repository;

    public function __construct(NoteRepository $repository)
    {
        parent::__construct($repository);
    }

    public function getItemsForUser(User $user): Collection
    {
        return $this
            ->repository
            ->where('user', $user->id());
    }
}
