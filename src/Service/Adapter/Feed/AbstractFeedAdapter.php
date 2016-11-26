<?php

namespace Service\Adapter\Feed;

use Entity\FeedItem;
use Guzzle\GuzzleClient;
use Zend\Feed\Reader\Feed\FeedInterface;
use Zend\Feed\Reader\ReaderImportInterface;

/**
 * Class AbstractFeedAdapter
 * @package Service\Adapter\Feed
 */
abstract class AbstractFeedAdapter implements FeedAdapterInterface
{
    /**
     * @var ReaderImportInterface $reader
     */
    protected $reader;

    /**
     * @param ReaderImportInterface $reader
     */
    public function __construct(ReaderImportInterface $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @return FeedItem[]
     */
    public function read()
    {
        $client = new GuzzleClient();
        $feed = $this->reader->importRemoteFeed($this->getFeedUrl(), $client);

        $map = [];
        foreach ($feed as $entry) {
            /** @var $entry FeedInterface */

            $content = strip_tags($entry->getDescription());
            $content = trim(str_replace('Read more...', '', $content));

            $map[] = new FeedItem(
                $entry->getId(),
                $entry->getTitle(),
                $content,
                $entry->getLink(),
                $this->getName()
            );
        }

        return $map;
    }

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @return string
     */
    abstract public function getFeedUrl();
}

