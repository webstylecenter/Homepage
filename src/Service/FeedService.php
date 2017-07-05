<?php

namespace Service;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOException;
use Entity\FeedItem;
use Entity\Feed;
use Guzzle\GuzzleClient;
use Zend\Feed\Reader\Reader;

/**
 * Class FeedService
 * @package Service
 */
class FeedService
{
    const DEFAULT_ITEM_LIMIT = 50;

    /**
     * @var Connection
     */
    protected $database;

    /**
     * @var string $lastError
     */
    protected $lastError;

    /**
     * @param Connection $database
     */
    public function __construct(Connection $database)
    {
        $this->database = $database;
    }

    /**
     * @return array
     */
    public function getFeeds()
    {
        $feedList = $this->database->fetchAll('SELECT * FROM feeds');

        $feeds = array_map(function($feed) {
            return $this->toFeedEntity($feed);
        }, $feedList);

        return $feeds;
    }

    public function import()
    {
        $feeds = $this->getFeeds();
        foreach ($feeds as $feed) {

            $feedItemList = $this->read($feed);
            foreach ($feedItemList as $feedItem) {

                if ($this->feedItemExists($feedItem)) {
                    continue;
                }

                $this->importFeedItem($feed, $feedItem);
            }
        }
    }

    /**
     * @return FeedItem[]
     */
    public function read($feed)
    {
        $client = new GuzzleClient();
        $reader = new Reader();
        $feedReader = $reader->importRemoteFeed($feed->getUrl(), $client);
        $map = [];
        foreach ($feedReader as $entry) {
            /** @var $entry FeedInterface */
            $content = strip_tags($entry->getDescription());
            $content = trim(str_replace('Read more...', '', $content));
            $map[] = new FeedItem(
                $entry->getId(),
                $entry->getTitle(),
                $content,
                $entry->getLink(),
                $feed->getId()
            );
        }
        return $map;
    }

    /**
     * @param FeedItem $feedItem
     *
     * @return bool
     */
    public function feedItemExists($feedItem)
    {
        return !!$this->database->fetchColumn(
            'SELECT COUNT(*) FROM feed_data WHERE guid = ? LIMIT 1',
            [$feedItem->getId()],
            0,
            [\PDO::PARAM_STR]
        );
    }

    /**
     * @param int $limit
     * @param \DateTime|null $fromDate
     * @param int $startFrom
     * @param string $searchQuery
     *
     * @return array
     */
    public function getFeedItems($limit = self::DEFAULT_ITEM_LIMIT, \DateTime $fromDate = null, $startFrom = 0, $searchQuery = '')
    {
        $fromDate = $fromDate ?: new \DateTime('@0');
        $feedItems = $this->database->fetchAll(
            'SELECT * FROM feed_data WHERE dateAdded > ? AND (title LIKE ? OR description LIKE ?) ORDER BY pinned DESC, dateAdded DESC LIMIT ?, ?',
                [$fromDate->format('Y-m-d H:i:s'),
                    '%' . $searchQuery . '%', '%' . $searchQuery . '%',
                    ($startFrom * $limit), $limit
                ],
                [
                    \PDO::PARAM_STR,
                    \PDO::PARAM_STR, \PDO::PARAM_STR,
                    \PDO::PARAM_INT, \PDO::PARAM_INT
                ]
        );

        $feed = array_map(function($feedItem) {
            return $this->toEntity($feedItem);
        }, $feedItems);

        return $feed;
    }

    /**
     * @param array $sites
     *
     * @return array
     */
    public function getFeedItemsBySites(array $sites)
    {
        $params = str_repeat('?,', count($sites) - 1) . '?';
        $feedItems = $this->database->fetchAll(
        'SELECT * FROM feed_data WHERE site IN (' . $params . ') ORDER BY pinned DESC, dateAdded DESC LIMIT 50',
            $sites
        );

        $feed = array_map(function($feedItem) {
            return $this->toEntity($feedItem);
        }, $feedItems);

        return $feed;
    }

    /**
     * @param Feed $feed
     * @param FeedItem $feedItem
     */
    protected function importFeedItem(Feed $feed, FeedItem $feedItem)
    {
        try {
            $this->database->insert('feed_data', [
                'guid' => $feedItem->getId(),
                'feed' => $feed->getId(),
                'title' => $feedItem->getTitle(),
                'description' => $feedItem->getDescription(),
                'url' => $feedItem->getUrl(),
                'dateAdded' => (new \DateTime())->format('Y-m-d H:i:s'),
                'viewed' => 0
            ]);
        } catch (PDOException $e) {
            // do nothing.
        }
    }

    /**
     * @param array $data
     *
     * @return FeedItem
     */
    protected function toEntity(array $data)
    {
        $feedItem = new FeedItem(
            $data['id'],
            $data['title'],
            $data['description'],
            $data['url'],
            $data['feed']
        );

        $feedItem->setViewed($data['viewed']);
        $feedItem->setDateAdded(new \DateTime($data['dateAdded']));
        $feedItem->setPinned($data['pinned']);
        return $feedItem;
    }

    /**
     * @param array $data
     *
     * @return Feed
     */
    protected function toFeedEntity(array $data)
    {
        return new Feed(
            $data['id'],
            $data['name'],
            $data['url'],
            $data['color']
        );
    }

    /**
     * @return array
     */
    public function getFeedItemTotals()
    {
        return $this->database->fetchAll('SELECT feed,COUNT(*) as count FROM feed_data GROUP BY feed ORDER BY count DESC;');
    }

    public function markAllViewed()
    {
        $this->database->update('feed_data', ['viewed' => 1], ['viewed' => 0]);
    }

    /**
     * @param int $id
     *
     * @return boolean
     */
    public function pinItem($id)
    {
        $feedItem = $this->database->fetchAssoc(
            'SELECT pinned FROM feed_data WHERE id = ?',
            [$id],
            [\PDO::PARAM_INT]
        );

        $newPinState = $feedItem['pinned'] == 1 ? NULL : 1;
        return $this->database->update('feed_data', ['pinned' => $newPinState], ['id' => $id]);
    }

    /**
     * @param FeedItem $feedItem
     *
     * @return string
     */
    public function addItem(FeedItem $feedItem)
    {
        try {
            $this->database->insert('feed_data', [
                'guid' => $feedItem->getId(),
                'feed' => '0',
                'title' => $feedItem->getTitle(),
                'description' => $feedItem->getDescription(),
                'url' => $feedItem->getUrl(),
                'dateAdded' => $feedItem->getDateAdded()->format('Y-m-d H:i:s'),
                'viewed' => (int) $feedItem->isViewed(),
                'pinned' => (int) $feedItem->isPinned(),
            ]);

            return true;
        } catch (PDOException $e) {
            $this->setLastError($e);
        }

        return false;
    }

    /**
     * @return string
     */
    public function getLastError()
    {
        return $this->lastError;
    }

    /**
     * @param string $lastError
     */
    public function setLastError($lastError)
    {
        $this->lastError = $lastError;
    }
}
