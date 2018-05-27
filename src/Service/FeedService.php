<?php

namespace App\Service;

use App\Entity\Feed;
use App\Entity\FeedListFilter;
use App\Entity\User;
use App\Entity\UserFeed;
use App\Entity\UserFeedItem;
use App\Repository\FeedRepository;
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
     * @var FeedRepository
     */
    protected $feedRepository;

    /**
     * @param UserFeedItemRepository $userFeedItemRepository
     * @param UserFeedRepository $userFeedRepository
     */
    public function __construct(UserFeedItemRepository $userFeedItemRepository, UserFeedRepository $userFeedRepository, FeedRepository $feedRepository)
    {
        $this->userFeedItemRepository = $userFeedItemRepository;
        $this->userFeedRepository = $userFeedRepository;
        $this->feedRepository = $feedRepository;
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

    public function findOrCreateFeedByUrl($url)
    {
        if ($feed = $this->feedRepository->findOneBy(['url' => $url])) {
            return $feed;
        }
        return new Feed();
    }

    /**
     * @param User $user
     * @return UserFeed[]
     */
    public function getUserFeeds(User $user)
    {
        return $this->userFeedRepository->getAllForUser($user);
    }

    /**
     * @param Feed $feed
     */
    public function persistFeed(Feed $feed)
    {
        return $this->feedRepository->persist($feed);
    }

    /**
     * @param UserFeed $userFeed
     */
    public function persistUserFeed(UserFeed $userFeed)
    {
        return $this->userFeedRepository->persist($userFeed);
    }
}
