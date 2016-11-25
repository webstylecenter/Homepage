<?php

namespace Service;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOException;
use Entity\FeedItem;
use Service\Adapter\Feed\FeedAdapterInterface;
use Zend\Feed\Reader\Reader;

class FeedService
{
    const DEFAULT_ITEM_LIMIT = 50;

    /**
     * @var FeedAdapterInterface[]
     */
    protected $adapters = [];

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
     * @param string $name
     * @param string $feedAdapter
     */
    public function registerAdapter($name, $feedAdapter)
    {
        $feedAdapterInstance = new $feedAdapter(new Reader());
        if (!$feedAdapterInstance instanceof FeedAdapterInterface) {
            throw new \InvalidArgumentException('FeedAdapter should be an instance of ' . FeedAdapterInterface::class);
        }

        $this->adapters[$name] = $feedAdapterInstance;
    }

    /**
     * @return FeedAdapterInterface[]
     */
    public function getAdapters()
    {
        return $this->adapters;
    }

    public function import()
    {
        foreach ($this->adapters as $name => $adapter) {
            $feedItemList = $adapter->read();

            foreach ($feedItemList as $feedItem) {
                if ($this->feedItemExists($feedItem)) {
                    continue;
                }

                $this->importFeedItem($feedItem, $adapter);
            }
        }
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
     *
     * @param \DateTime|null $fromDate
     *
     * @return \Entity\FeedItem[]
     */
    public function getFeedItems($limit = self::DEFAULT_ITEM_LIMIT, \DateTime $fromDate = null, $startFrom = 0)
    {
        $fromDate = $fromDate ?: new \DateTime('@0');

        $feedItems = $this->database->fetchAll(
            'SELECT * FROM feed_data WHERE dateAdded > ? ORDER BY pinned DESC, dateAdded DESC LIMIT ?, ?',
            [$fromDate->format('Y-m-d H:i:s'), ($startFrom * $limit), $limit],
            [\PDO::PARAM_STR, \PDO::PARAM_INT, \PDO::PARAM_INT]
        );

        $feed = array_map(function($feedItem) {
            return $this->toEntity($feedItem);
        }, $feedItems);

        return $feed;
    }

    /**
     * @param FeedItem $feedItem
     * @param FeedAdapterInterface $feedAdapter
     */
    protected function importFeedItem(FeedItem $feedItem, FeedAdapterInterface $feedAdapter)
    {
        try {
            $this->database->insert('feed_data', [
                'guid' => $feedItem->getId(),
                'site' => $feedAdapter->getName(),
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
            $data['site']
        );

        $feedItem->setViewed($data['viewed']);
        $feedItem->setDateAdded(new \DateTime($data['dateAdded']));
        $feedItem->setPinned($data['pinned']);
        return $feedItem;
    }

    /**
     * @return array
     */
    public function getFeedItemTotals()
    {
        return $this->database->fetchAll('SELECT site,COUNT(*) as count FROM feed_data GROUP BY site ORDER BY count DESC;');
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
                'site' => 'userInput',
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
