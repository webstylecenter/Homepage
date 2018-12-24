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
     * @param User $user
     * @param int $userFeedItemId
     */
    public function setOpenedItemsForUser(User $user, $userFeedItemId)
    {
        $userFeedItem = $this->userFeedItemRepository->findOneBy(['id' => $userFeedItemId, 'user' => $user]);
        $this->userFeedItemRepository->setOpenedItemsForUser($userFeedItem);
    }

    /**
     * @param User $user
     * @return UserFeedItem[]
     */
    public function getOpenedItemsForUser(User $user)
    {
        return $this->userFeedItemRepository->getOpenedItemsForUser($user);
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

    /**
     * @param $url
     * @return Feed|null
     */
    public function findOrCreateFeedByUrl($url)
    {
        if ($feed = $this->feedRepository->findOneBy(['url' => $url])) {
            return $feed;
        }
        return new Feed();
    }

    /**
     * @param $id
     * @return UserFeedItem
     */
    public function findUserFeedItemUrl($id)
    {
        return $this->userFeedItemRepository->findOneBy(['id' => $id]);
    }

    /**
     * @param User $user
     * @return UserFeed[]
     */
    public function getUserFeeds(User $user)
    {
        $userFeeds = $this->userFeedRepository->getAllForUser($user);
        return $this->sortUserFeeds($userFeeds);
    }

    /**
     * @return Feed[]
     */
    public function getFeeds()
    {
        return $this->feedRepository->findBy([], ['name' => 'ASC']);
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

    /**
     * @param UserFeed $userFeed
     */
    public function removeUserFeed(UserFeed $userFeed)
    {
        $this->userFeedRepository->remove($userFeed);

        $users = $this->userFeedRepository->findBy(['feed' => $userFeed->getFeed()]);
        if (count($users) === 0) {
            $this->feedRepository->remove($userFeed->getFeed());
        }
    }

    /**
     * @param $feedId
     * @return Feed|null
     */
    public function getFeedById($feedId)
    {
        return $this->feedRepository->findOneBy(['id' => $feedId]);
    }

    /**
     * @param User $user
     * @return array
     */
    public function getAvailableFeeds(User $user)
    {
        $userFeeds = $this->getUserFeeds($user);
        $feeds = $this->getFeeds();

        $availableFeeds = [];
        foreach ($feeds as $feed) {
            $found = false;
            foreach ($userFeeds as $userFeed) {
                if ($userFeed->getFeed() === $feed) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $availableFeeds[] = $feed;
            }
        }

        return $availableFeeds;
    }

    /**
     * @param $userFeeds
     * @return mixed
     */
    private function sortUserFeeds($userFeeds)
    {
        usort($userFeeds, function($a, $b)
        {
            return strcmp($a->getFeed()->getName(), $b->getFeed()->getName());
        });

        return $userFeeds;
    }
}
