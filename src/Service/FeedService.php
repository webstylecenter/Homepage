<?php

namespace Service;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOException;
use Entity\FeedItem;
use Entity\Feed;
use Guzzle\GuzzleClient;
use Zend\Feed\Reader\Entry\EntryInterface;
use Zend\Feed\Reader\Reader;

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
     * @param callable $onFeedImported
     * @param callable $onFeedImportFailed
     */
    public function import(callable $onFeedImported, callable $onFeedImportFailed)
    {
        foreach ($this->getFeeds() as $feed) {
            if (!$feed->getFeedUrl()) {
                continue;
            }

            try {
                foreach ($this->read($feed) as $feedItem) {
                    $this->importFeedItem($feed, $feedItem);
                }
                $onFeedImported($feed->getName());
            } catch (\Exception $exception) {
                $onFeedImportFailed($feed->getName(), $exception);
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
            return new Feed($feed['id'], $feed['name'], $feed['feedUrl'], $feed['color'], $feed['icon'], $feed['autoPin']);
        }, $feedList);
    }

    /**
     * @param FeedItem $feedItem
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
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
     * @param string|null $searchQuery
     *
     * @return FeedItem[]
     */
    public function getFeedItems($limit = self::DEFAULT_ITEM_LIMIT, \DateTime $fromDate = null, $startIndex = 0, $searchQuery = null)
    {
        $fromDate = $fromDate ?: new \DateTime('@0');

        $params = [$fromDate->format('Y-m-d H:i:s')];
        if ($searchQuery) {
            $params[] = '*' . $searchQuery . '*';
        }

        $feedItems = $this->database->fetchAll("
            SELECT feed_data.*, feeds.color as feedColor, feeds.name as feedName, feeds.icon as feedIcon
            FROM feed_data 
            LEFT JOIN feeds 
            ON feed_data.feed = feeds.id 
            WHERE feed_data.dateAdded > ? 
            " . ($searchQuery ? ' AND MATCH(title, description) AGAINST(? IN BOOLEAN MODE)' : null) . " 
            ORDER BY feed_data.pinned DESC, feed_data.dateAdded DESC 
            LIMIT " . (int) $limit . " 
            OFFSET " . (int) ($startIndex * $limit),
            $params,
            array_fill(0, count($params), \PDO::PARAM_STR)
        );

        return array_map([$this, 'toFeedItemEntity'], $feedItems);
    }

    /**
     * @param array $sites
     * @return FeedItem[]
     */
    public function getFeedItemsBySites(array $sites)
    {
        $feedItems = $this->database->fetchAll("
            SELECT feed_data.*, feeds.color as feedColor, feeds.name as feedName, feeds.icon as feedIcon
            FROM feed_data 
            LEFT JOIN feeds 
            ON feed_data.feed = feeds.id 
            WHERE feed IN (" . (str_repeat('?,', count($sites) - 1) . '?') . ")
            ORDER BY feed_data.pinned DESC, feed_data.dateAdded DESC
            LIMIT 100",
            $sites
        );

        return array_map([$this, 'toFeedItemEntity'], $feedItems);
    }

    /**
     * @return array
     */
    public function getFeedItemTotals()
    {
        return $this->database->fetchAll("
            SELECT feed, COUNT(*) as count, name, feed_data.dateAdded 
            FROM feed_data 
            LEFT JOIN feeds 
            ON feed_data.feed = feeds.id 
            GROUP BY feed 
            ORDER BY count DESC
        ");
    }

    public function markAllViewed()
    {
        $this->database->update('feed_data', ['viewed' => 1], ['viewed' => 0]);
    }

    /**
     * @param $id
     * @throws \Doctrine\DBAL\DBALException
     */
    public function pinItem($id)
    {
        $this->database->query('UPDATE feed_data SET pinned = !pinned WHERE id = ' . (int) $id);
    }

    /**
     * @param FeedItem $feedItem
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function addItem(FeedItem $feedItem)
    {
        try {
            $this->database->insert('feed_data', [
                'guid' => $feedItem->getId(),
                'feed' => 0,
                'title' => (strlen($feedItem->getTitle()) > 250 ? substr($feedItem->getTitle(), 0, 250) . '...' : $feedItem->getTitle()),
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
     * @param $autoPin
     * @param $feedIcon
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    public function addFeed($name, $url, $color, $autoPin, $feedIcon)
    {
        if (!$name || !$url || !$color) {
            throw new \Exception('Not all feed data given');
        }

        return $this->database->insert('feeds', [
            'name' => $name,
            'feedUrl' => $url,
            'color' => $color,
            'icon' => $feedIcon,
            'autoPin' => $autoPin,
            'created' => (new \DateTime())->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * @param int $feedId
     * @param string $setting
     * @param string $value
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    public function updateFeedSetting($feedId, $setting, $value)
    {
        return $this->database->update('feeds',
            [$setting => $value],
            ['id'=>$feedId]
        );
    }

    /**
     * @param $feedId
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    public function removeFeed($feedId)
    {
        if (!$feedId) {
            throw new Exception('No feed Id given to remove');
        }

        $this->database->delete('feed_data', ['feed' => $feedId]);
        return $this->database->delete('feeds', ['id' => $feedId]);
    }

    /**
     * @return array
     */
    public function getFeedOverview()
    {
        $feedOverview = [];
        $feeds = $this->getFeeds();
        foreach ($feeds as $feed) {
            $feedOverview[$feed->getName()] = $this->getFeedItemsBySites([$feed->getId()]);
        }
        ksort($feedOverview);
        return $feedOverview;
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

            return new FeedItem(intval($entry->getId()), $entry->getTitle(), $content, $entry->getLink(), $feed->getId());
        }, $entries);
    }

    /**
     * @param Feed $feed
     * @param FeedItem $feedItem
     * @throws \Doctrine\DBAL\DBALException
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
                'title' => (strlen($feedItem->getTitle()) > 250 ? substr($feedItem->getTitle(), 0, 250) . '...' : $feedItem->getTitle()),
                'description' => $feedItem->getDescription(),
                'url' => $feedItem->getUrl(),
                'dateAdded' => (new \DateTime())->format('Y-m-d H:i:s'),
                'viewed' => 0,
                'pinned' => $feed->isAutoPin()
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
            ->setColor($data['feedColor'])
            ->setFeedIcon($data['feedIcon'])
            ->setFeedName($data['feedName']);
    }
}
