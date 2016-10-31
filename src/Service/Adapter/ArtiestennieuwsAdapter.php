<?php

namespace Service\Adapter;

use Entity\FeedItem;
use Zend\Feed\Reader\Feed\FeedInterface;
use Zend\Feed\Reader\ReaderImportInterface;
use Zend\Feed\Reader\Http\Client;


class ArtiestennieuwsAdapter implements FeedAdapterInterface
{
    const FEED_URL = 'http://www.artiestennieuws.nl/feed/';
    const NAME = 'Artiesten Nieuws';

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
        // TODO: Use ClientInterface to parse feed the usual way
        $feedSrc = file_get_contents(self::FEED_URL);
        $feed = $this->reader->importString($feedSrc);

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
                self::NAME
            );
        }

        return $map;
    }

    public function getName()
    {
        return self::NAME;
    }
}

