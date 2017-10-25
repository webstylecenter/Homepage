<?php

namespace Service;

use Doctrine\DBAL\Connection;

class RedirectService
{
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
     * @param int $id
     * @param int $feedName
     *
     * @return string
     */
    public function getSharedFeedItem($id, $feedName)
    {
        // TODO: Do a feedName check to see if it matches the selected feedItem for security purposes
        $feedItem = $this->database->fetchAll('SELECT url FROM feed_data WHERE id = ? LIMIT 1', [$id], [\PDO::PARAM_INT]);
        return $feedItem[0]['url'];
    }
}
