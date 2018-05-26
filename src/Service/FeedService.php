<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\FeedItemRepository;
use App\Repository\FeedRepository;
use App\Entity\Feed;
use App\Entity\FeedItem;
use App\Guzzle\GuzzleClient;
use Doctrine\ORM\EntityManagerInterface;
use Zend\Feed\Reader\Entry\EntryInterface;
use Zend\Feed\Reader\Reader;

class FeedService
{
    const DEFAULT_ITEM_LIMIT = 50;

    /**
     * @var string $lastError
     */
    protected $lastError;

    /**
     * @var FeedRepository
     */
    protected $feedRepository;

    /**
     * @var FeedItemRepository
     */
    protected $feedItemRepository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(FeedRepository $feedRepository, FeedItemRepository $feedItemRepository, EntityManagerInterface $entityManager)
    {
        $this->feedRepository = $feedRepository;
        $this->feedItemRepository = $feedItemRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param callable $onFeedImported
     * @param callable $onFeedImportFailed
     */
    public function import(callable $onFeedImported, callable $onFeedImportFailed)
    {
        $feeds = $this->feedRepository->findAll();

        foreach ($feeds as $feed) {
            if (!$feed->getFeedUrl()) {
                continue;
            }

            try {
                foreach ($this->read($feed) as $feedItem) {
                    if ($feedItem !== null) {
                        $this->entityManager->persist($feedItem);
                        $this->entityManager->flush();
                    }
                }
                $onFeedImported($feed->getName());
            } catch (\Exception $exception) {
                $onFeedImportFailed($feed->getName(), $exception);
            }
        }
    }

    /**
     * @param User $user
     * @throws \Doctrine\DBAL\DBALException
     */
    public function markAllViewed(User $user)
    {
        $this->entityManager->getConnection()->update('feed_item', ['viewed' => 1], ['viewed' => 0, 'user_id' => $user->getId()]);
    }

    /**
     * @param Feed $feed
     * @return string
     */
    public function getFeedName(Feed $feed)
    {
        $feed = ((new Reader)->importRemoteFeed($feed->getFeedUrl(), new GuzzleClient));
        return $feed->getTitle();
    }

    /**
     * @param Feed $feed
     * @return FeedItem[]
     */
    public function read(Feed $feed)
    {
        $entries = iterator_to_array((new Reader)->importRemoteFeed($feed->getFeedUrl(), new GuzzleClient));
        return array_map(function (EntryInterface $entry) use ($feed) {
            $content = strip_tags($entry->getDescription());
            $content = trim(str_replace('Read more...', '', $content));

            if (!$this->feedItemRepository->findBy(['guid' => $entry->getId()])) {
                $feedItem = new FeedItem();
                $feedItem
                    ->setTitle($entry->getTitle())
                    ->setGuid($entry->getId())
                    ->setDescription($content)
                    ->setUrl($entry->getLink())
                    ->setPinned($feed->getAutoPin())
                    ->setViewed(false)
                    ->setFeed($feed)
                    ->setUser($feed->getUser());

                return $feedItem;
            }

            return null;
        }, $entries);
    }
}
