<?php

namespace Service\Adapter;

use Entity\FeedItem;
use Zend\Feed\Reader\Feed\FeedInterface;
use Zend\Feed\Reader\ReaderImportInterface;

class NeowinAdapter implements FeedAdapterInterface
{
    const FEED_URL = 'http://feeds.feedburner.com/neowin-main';

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
        $feed = $this->reader->import(self::FEED_URL);

        $map = [];
        foreach ($feed as $entry) {
            /** @var $entry FeedInterface */

            $content = strip_tags($entry->getDescription());
            $content = trim(str_replace('Read more...', '', $content));

            $map[] = new FeedItem(
                $entry->getId(),
                $entry->getTitle(),
                $content
            );
        }

        return $map;
	}
}

