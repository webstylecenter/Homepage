<?php

namespace Service;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOException;
use Service\Adapter\FeedAdapterInterface;
use Zend\Feed\Reader\Reader;

class FeedService
{
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
                try {
                    $this->database->insert('feedItem', [
                        'id' => $feedItem->getId(),
                        //..
                    ]);
                } catch(PDOException $e) {
                    // do nothing.
                }
            }
        }
    }
}
