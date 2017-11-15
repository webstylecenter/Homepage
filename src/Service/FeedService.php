<?php

namespace Service;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOException;
use Entity\FeedItem;
use Entity\Feed;
use Guzzle\GuzzleClient;
use Symfony\Component\Security\Acl\Exception\Exception;
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
            if (!$feed->getFeedUrl()) {
                continue;
            }

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
        $searchQueryString = $searchQuery ? ' AND MATCH(title, description) AGAINST(? IN BOOLEAN MODE)' : null;

        $types = [\PDO::PARAM_STR, \PDO::PARAM_INT, \PDO::PARAM_INT];
        $params = [$fromDate->format('Y-m-d H:i:s'), ($startIndex * $limit), $limit];
        if ($searchQuery) {
            $types = [\PDO::PARAM_STR, \PDO::PARAM_STR, \PDO::PARAM_INT, \PDO::PARAM_INT];
            $params = [$fromDate->format('Y-m-d H:i:s'), '*' . $searchQuery . '*', ($startIndex * $limit), $limit];
        }

        $feedItems = $this->database->fetchAll(
            'SELECT *, feed_data.id AS itemId  FROM feed_data LEFT JOIN feeds ON feed_data.feed = feeds.id WHERE feed_data.dateAdded > ? ' . $searchQueryString . ' ORDER BY feed_data.pinned DESC, feed_data.dateAdded DESC LIMIT ?, ?',
            $params,
            $types
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
        'SELECT *, feed_data.id AS itemId FROM feed_data LEFT JOIN feeds ON feed_data.feed = feeds.id WHERE feed IN (' . $params . ') ORDER BY feed_data.pinned DESC, feed_data.dateAdded DESC LIMIT 50',
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
        return $this->database->fetchAll('SELECT feed,COUNT(*) as count, name, feed_data.dateAdded FROM feed_data LEFT JOIN feeds ON feed_data.feed = feeds.id GROUP BY feed ORDER BY count DESC');
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
        $feedItem = $this->database->fetchAssoc('SELECT pinned FROM feed_data WHERE id = ?', [$id], [\PDO::PARAM_INT]);
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
            $this->lastError = (string) $e;
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
     * @param $name
     * @param $url
     * @param $color
     *
     * @return int
     */
    public function addFeed($name, $url, $color)
    {
        if (empty($name) || empty($url) || empty($color)) {
            throw new Exception('Not all feed data given');
        }

        return $this->database->insert('feeds', [
            'name' => $name,
            'feedUrl' => $url,
            'color' => $color,
            'created' => (new \DateTime())->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * @param $feedId
     *
     * @return int
     */
    public function removeFeed($feedId)
    {
        if (!isset($feedId) || empty($feedId)) {
            throw new Exception('No feed Id given to remove');
        }

        $this->database->delete('feed_data', [
            'feed' => $feedId
        ]);

        return $this->database->delete('feeds', [
            'id' => $feedId
        ]);
    }

    /**
     * @param Feed $feed
     * @return FeedItem[]
     */
    protected function read(Feed $feed)
    {
        $entries = iterator_to_array((new Reader)->importRemoteFeed($feed->getFeedUrl(), new GuzzleClient));
        return array_map(function(EntryInterface $entry) use ($feed) {
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
