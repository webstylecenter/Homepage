<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\FeedRepository;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\UserFeedItemRepository;
use App\Repositories\UserFeedRepository;
use App\Services\Interfaces\AbstractServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class FeedService extends AbstractService implements AbstractServiceInterface
{
    protected RepositoryInterface $repository;
    protected UserFeedItemRepository $userFeedItemRepository;
    protected UserFeedRepository $userFeedRepository;

    public function __construct(FeedRepository $repository)
    {
        parent::__construct($repository);
    }

    public function all(): Collection
    {
        return $this->repository->all()->sortBy(['name' => 'asc']);
    }
}
