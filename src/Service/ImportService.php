<?php

namespace App\Service;

use App\Entity\UserFeedItem;
use App\Repository\FeedItemRepository;
use App\Repository\FeedRepository;
use App\Entity\Feed;
use App\Entity\FeedItem;
use App\Guzzle\GuzzleClient;
use App\Repository\UserFeedItemRepository;
use App\Repository\UserFeedRepository;
use Zend\Feed\Reader\Entry\EntryInterface;
use Zend\Feed\Reader\Reader;

class ImportService
{
    /**
     * @var FeedRepository
     */
    protected $feedRepository;

    /**
     * @var FeedItemRepository
     */
    protected $feedItemRepository;

    /**
     * @var UserFeedRepository
     */
    protected $userFeedRepository;

    /**
     * @var UserFeedItemRepository
     */
    protected $userFeedItemRepository;

    /**
     * @param FeedRepository $feedRepository
     * @param FeedItemRepository $feedItemRepository
     * @param UserFeedRepository $userFeedRepository
     * @param UserFeedItemRepository $userFeedItemRepository
     */
    public function __construct(FeedRepository $feedRepository, FeedItemRepository $feedItemRepository, UserFeedRepository $userFeedRepository, UserFeedItemRepository $userFeedItemRepository)
    {
        $this->feedRepository = $feedRepository;
        $this->feedItemRepository = $feedItemRepository;
        $this->userFeedRepository = $userFeedRepository;
        $this->userFeedItemRepository = $userFeedItemRepository;
    }

    /**
     * @param callable $onFeedImported
     * @param callable $onFeedImportFailed
     */
    public function import(callable $onFeedImported, callable $onFeedImportFailed)
    {
        $feeds = $this->feedRepository->findAll();

        foreach ($feeds as $feed) {
            if (!$feed->getUrl()) {
                continue;
            }

            try {
                foreach ($this->read($feed) as $feedItem) {
                    if ($feedItem !== null) {
                        $this->feedItemRepository->persist($feedItem);
                    }
                }
                $onFeedImported($feed->getName());
            } catch (\Exception $exception) {
                $onFeedImportFailed($feed->getName(), $exception);
            }
        }
    }

    /**
     * @param Feed $feed
     * @return string
     */
    public function getFeedName(Feed $feed)
    {
        return ((new Reader)
            ->importRemoteFeed($feed->getUrl(), new GuzzleClient))
            ->getTitle();
    }

    /**
     * @param Feed $feed
     * @return FeedItem[]
     */
    public function read(Feed $feed)
    {
        $entries = iterator_to_array((new Reader)->importRemoteFeed($feed->getUrl(), new GuzzleClient));
        return array_map(function (EntryInterface $entry) use ($feed) {
           $feedItem = $this->getFeedItemForEntry($entry, $feed);
            !$feedItem ?: $this->feedItemRepository->persist($feedItem);
        }, $entries);
    }

    /**
     * Note: This will only add new feed-items to every user. If a user just subscribed, old items won't be added
     *
     * @param EntryInterface $entry
     * @param Feed $feed
     * @return FeedItem|null
     */
    protected function getFeedItemForEntry(EntryInterface $entry, Feed $feed)
    {
        $content = strip_tags($entry->getDescription());
        $content = trim(str_replace('Read more...', '', $content));

        if ($this->feedItemRepository->findBy(['guid' => $entry->getId()])) {
            return null;
        }

        $feedItem = new FeedItem();
        $feedItem->setTitle($entry->getTitle());
        $feedItem->setGuid($entry->getId());
        $feedItem->setDescription($content);
        $feedItem->setUrl($entry->getLink());
        $feedItem->setFeed($feed);

        //$users = $this->use->findOneBy(['id' => $feed->getId()]);
        $userFeeds = $this->userFeedRepository->findBy(['feed'=> $feed]);

        foreach ($userFeeds as $userFeed) {
            $userFeedItem = new UserFeedItem();
            $userFeedItem->setUserFeed($userFeed);
            $userFeedItem->setUser($userFeed->getUser());
            $userFeedItem->setPinned($userFeed->isAutoPin());
            $userFeedItem->setFeedItem($feedItem);
            $userFeedItem->setViewed(false);

            $this->userFeedItemRepository->persist($userFeedItem);
        }

        return $feedItem;
    }


}
