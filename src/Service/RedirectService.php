<?php

namespace Service;

use Entity\FeedItem;
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

    /**
     * @param array $data
     * @return FeedItem
     */
    protected function toFeedItemEntity(array $data)
    {
        return (new FeedItem($data['itemId'], $data['title'], $data['description'], $data['url'], $data['feed']))
            ->setViewed($data['viewed'])
            ->setDateAdded(new \DateTime($data['dateAdded']))
            ->setPinned($data['pinned'])
            ->setColor($data['color'])
            ->setFeedName($data['name']);
    }
}
