<?php

namespace Service;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOException;
use Entity\FeedItem;
use Service\Adapter\FeedAdapterInterface;
use Zend\Feed\Reader\Extension\Atom\Feed;
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
                    try {
                        $this->database->insert('feed_data  ', [
                            'guid' => $feedItem->getId(),
                            'site' => $adapter->getName(),
                            'title' => $feedItem->getTitle(),
                            'description' => $feedItem->getDescription(),
                            'url' => $feedItem->getUrl(),
                            'dateAdded' => date('Y-m-d H:i:s'),
                            'viewed' => 0
                        ]);
                    } catch(PDOException $e) {
                        // do nothing.
                    }
                }
            }
        }
    }

    /**
     * @param $feedItem
     *
     * @return bool
     */
    public function feedItemExists($feedItem)
    {
        $results = $this->database->fetchAll(
            'SELECT * FROM feed_data WHERE guid = ?',
            [$feedItem->getId()],
            [\PDO::PARAM_STR]
        );

        return (count($results) == 0 ? true : false);
    }

    /**
     * @param int $limit
     *
     * @return FeedItem[]
     */
    public function getFeedItems($limit = self::DEFAULT_ITEM_LIMIT)
    {
        $feedItems = $this->database->fetchAll(
            'SELECT * FROM feed_data ORDER BY dateAdded DESC LIMIT ?',
            [$limit],
            [\PDO::PARAM_INT]
        );

        return array_map(function($feedItem) {
            $feedItemInstance = new FeedItem(
                $feedItem['id'],
                $feedItem['title'],
                $feedItem['description'],
                $feedItem['url']
            );

            $feedItemInstance->setViewed($feedItem['viewed']);
            $feedItemInstance->setDateAdded(new \DateTime($feedItem['dateAdded']));
            return $feedItemInstance;

        }, $feedItems);
    }

}
