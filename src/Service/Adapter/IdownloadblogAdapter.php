<?php

namespace Service\Adapter;

use Entity\FeedItem;
use Zend\Feed\Reader\Feed\FeedInterface;
use Zend\Feed\Reader\ReaderImportInterface;
use Zend\Feed\Reader\Reader as ZendFeedReader;
use Zend\Http\Client as ZendHttpClient;

class IdownloadblogAdapter implements FeedAdapterInterface
{
    const FEED_URL = 'https://www.idownloadblog.com/feed/';
    const NAME = 'iDownloadBlog';

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
        $httpClientOptions = array(
            'adapter'      => 'Zend\Http\Client\Adapter\Socket',
            'persistent'=>false,

            'sslverifypeer' => false,
            'sslallowselfsigned' => true,
            'sslusecontext'=>false,

            'ssl' => array(
                'verify_peer' => false,
                'allow_self_signed' => true,
                'capture_peer_cert' => true,
            ),

            'useragent' => 'Feed Reader',
        );

        ZendFeedReader::setHttpClient(new ZendHttpClient(null, $httpClientOptions));

        $feed = $this->reader->import(self::FEED_URL);

        $map = [];
        foreach ($feed as $entry) {
            /** @var $entry FeedInterface */

            $content = strip_tags($entry->getDescription());
            $content = trim(str_replace('Read more...', '', $content));

            $map[] = new FeedItem(
                $entry->getId(),
                $entry->getTitle(),
                $content,
                $entry->getLink()
            );
        }

        return $map;
    }

    public function getName()
    {
        return self::NAME;
    }
}

