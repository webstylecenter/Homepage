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
     * @param int $feedId
     *
     * @return string
     */
    public function getSharedFeedItem($id, $feedId)
    {
        $feedItem = $this->database->fetchAll(
            'SELECT url FROM feed_data WHERE id = ? AND feed = ? LIMIT 1',
            [
                $id,
                $feedId
            ],
            [
                \PDO::PARAM_INT,
                \PDO::PARAM_INT
            ]
        );

        return $feedItem[0]['url'];
    }
}
