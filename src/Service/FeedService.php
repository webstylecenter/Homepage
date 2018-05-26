<?php

namespace App\Service;

use App\Entity\FeedListFilter;
use App\Entity\User;
use App\Entity\UserFeed;
use App\Entity\UserFeedItem;
use App\Repository\UserFeedItemRepository;
use App\Repository\UserFeedRepository;

class FeedService
{
    /**
     * @var UserFeedItemRepository
     */
    protected $userFeedItemRepository;

    /**
     * @var UserFeedRepository
     */
    protected $userFeedRepository;

    /**
     * @param UserFeedItemRepository $userFeedItemRepository
     * @param UserFeedRepository $userFeedRepository
     */
    public function __construct(UserFeedItemRepository $userFeedItemRepository, UserFeedRepository $userFeedRepository)
    {
        $this->userFeedItemRepository = $userFeedItemRepository;
        $this->userFeedRepository = $userFeedRepository;
    }

    /**
     * @param User $user
     */
    public function setViewedForUser(User $user)
    {
        $this->userFeedItemRepository->setViewedForUser($user);
    }

    /**
     * @param UserFeedItem $userFeedItem
     */
    public function persistUserFeedItem(UserFeedItem $userFeedItem)
    {
        $this->userFeedItemRepository->persist($userFeedItem);
    }

    /**
     * @param FeedListFilter $feedListFilter
     * @return UserFeedItem[]
     */
    public function getUserFeedItemsWithFilter(FeedListFilter $feedListFilter)
    {
        return $this->userFeedItemRepository->getWithFilter($feedListFilter);
    }
}
