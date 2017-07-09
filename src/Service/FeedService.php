<?php

namespace Service;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOException;
use Entity\FeedItem;
use Entity\Feed;
use Guzzle\GuzzleClient;
use Zend\Feed\Reader\Entry\EntryInterface;
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

    public function import()
    {
        foreach ($this->getFeeds() as $feed) {
            foreach ($this->read($feed) as $feedItem) {
                $this->importFeedItem($feed, $feedItem);
            }
        }
    }

    /**
     * @return Feed[]
     */
    public function getFeeds()
    {
        $feedList = $this->database->fetchAll('SELECT * FROM feeds');

        return array_map(function($feed) {
            return new Feed($feed['id'], $feed['name'], $feed['feedUrl'], $feed['color']);
        }, $feedList);
    }

    /**
     * @param FeedItem $feedItem
     *
     * @return bool
     */
    public function feedItemExists(FeedItem $feedItem)
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
     * @param int $startIndex
     * @param string $searchQuery
     *
     * @return array
     */
    public function getFeedItems($limit = self::DEFAULT_ITEM_LIMIT, \DateTime $fromDate = null, $startIndex = 0, $searchQuery = '')
    {
        $fromDate = $fromDate ?: new \DateTime('@0');
        $feedItems = $this->database->fetchAll(
            'SELECT * FROM feed_data LEFT JOIN feeds ON feed_data.feed = feeds.id WHERE dateAdded > ? AND (title LIKE ? OR description LIKE ?) ORDER BY pinned DESC, dateAdded DESC LIMIT ?, ?',
                [
                    $fromDate->format('Y-m-d H:i:s'),
                    '%' . $searchQuery . '%', '%' . $searchQuery . '%',
                    ($startIndex * $limit), $limit
                ],
                [
                    \PDO::PARAM_STR,
                    \PDO::PARAM_STR, \PDO::PARAM_STR,
                    \PDO::PARAM_INT, \PDO::PARAM_INT
                ]
        );

        return array_map(function($feedItem) {
            return $this->toFeedItemEntity($feedItem);
        }, $feedItems);
    }

    /**
     * @param array $sites
     * @return array
     */
    public function getFeedItemsBySites(array $sites)
    {
        $params = str_repeat('?,', count($sites) - 1) . '?';
        $feedItems = $this->database->fetchAll(
        'SELECT * FROM feed_data LEFT JOIN feeds ON feed_data.feed = feeds.id WHERE feed IN (' . $params . ') ORDER BY pinned DESC, dateAdded DESC LIMIT 50',
            $sites
        );

        return array_map(function($feedItem) {
            return $this->toFeedItemEntity($feedItem);
        }, $feedItems);
    }

    /**
     * @return array
     */
    public function getFeedItemTotals()
    {
        return $this->database->fetchAll('SELECT feed,COUNT(*) as count, name FROM feed_data LEFT JOIN feeds ON feed_data.feed = feeds.id GROUP BY feed ORDER BY count DESC;');
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
        $feedItem = $this->database->fetchAssoc('SELECT pinned FROM feed_data WHERE id = ' . (int) $id);
        return $this->database->update('feed_data', ['pinned' => !!$feedItem['pinned']], ['id' => $id]);
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
            $this->lastError = (string)$e;
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
     * @param Feed $feed
     * @return FeedItem[]
     */
    protected function read(Feed $feed)
    {
        $entries = iterator_to_array((new Reader)->importRemoteFeed($feed->getFeedUrl(), new GuzzleClient));
        return array_map(function (EntryInterface $entry) use ($feed) {
            $content = strip_tags($entry->getDescription());
            $content = trim(str_replace('Read more...', '', $content));

            return new FeedItem($entry->getId(), $entry->getTitle(), $content, $entry->getLink(), $feed->getId());
        }, $entries);
    }

    /**
     * @param Feed $feed
     * @param FeedItem $feedItem
     */
    protected function importFeedItem(Feed $feed, FeedItem $feedItem)
    {
        if ($this->feedItemExists($feedItem)) {
            return;
        }

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
     * @return FeedItem
     */
    protected function toFeedItemEntity(array $data)
    {
        return (new FeedItem($data['id'], $data['title'], $data['description'], $data['url'], $data['feed']))
            ->setViewed($data['viewed'])
            ->setDateAdded(new \DateTime($data['dateAdded']))
            ->setPinned($data['pinned'])
            ->setColor($data['color'])
            ->setFeedName($data['name']);
    }
}
