<?php

namespace App\Service;

use App\Entity\UserFeedItem;
use App\Repository\FeedItemRepository;
use App\Repository\FeedRepository;
use App\Entity\Feed;
use App\Entity\FeedItem;
use App\Guzzle\GuzzleClient;
use App\Repository\UserFeedItemRepository;
use App\Repository\UserFeedRepository;
use Zend\Feed\Reader\Entry\EntryInterface;
use Zend\Feed\Reader\Reader;

class ImportService
{
    /**
     * @var FeedRepository
     */
    protected $feedRepository;

    /**
     * @var FeedItemRepository
     */
    protected $feedItemRepository;

    /**
     * @var UserFeedRepository
     */
    protected $userFeedRepository;

    /**
     * @var UserFeedItemRepository
     */
    protected $userFeedItemRepository;

    /**
     * @param FeedRepository $feedRepository
     * @param FeedItemRepository $feedItemRepository
     * @param UserFeedRepository $userFeedRepository
     * @param UserFeedItemRepository $userFeedItemRepository
     */
    public function __construct(FeedRepository $feedRepository, FeedItemRepository $feedItemRepository, UserFeedRepository $userFeedRepository, UserFeedItemRepository $userFeedItemRepository)
    {
        $this->feedRepository = $feedRepository;
        $this->feedItemRepository = $feedItemRepository;
        $this->userFeedRepository = $userFeedRepository;
        $this->userFeedItemRepository = $userFeedItemRepository;
    }

    /**
     * @param callable $onFeedImported
     * @param callable $onFeedImportFailed
     */
    public function import(callable $onFeedImported, callable $onFeedImportFailed)
    {
        $feeds = $this->feedRepository->findAll();

        foreach ($feeds as $feed) {
            if (!$feed->getUrl()) {
                continue;
            }

            try {
                foreach ($this->read($feed) as $feedItem) {
                    if ($feedItem !== null) {
                        $this->feedItemRepository->persist($feedItem);
                    }
                }
                $onFeedImported($feed->getName());
            } catch (\Exception $exception) {
                $onFeedImportFailed($feed->getName(), $exception);
            }
        }
    }

    /**
     * @param Feed $feed
     * @return string
     */
    public function getFeedName(Feed $feed)
    {
        return ((new Reader)
            ->importRemoteFeed($feed->getUrl(), new GuzzleClient))
            ->getTitle();
    }

    /**
     * @param Feed $feed
     * @return FeedItem[]
     */
    public function read(Feed $feed)
    {
        $entries = iterator_to_array((new Reader)->importRemoteFeed($feed->getUrl(), new GuzzleClient));
        return array_map(function (EntryInterface $entry) use ($feed) {
            $feedItem = $this->getFeedItemForEntry($entry, $feed);
            !$feedItem ?: $this->feedItemRepository->persist($feedItem);
        }, $entries);
    }

    /**
     * Note: This will only add new feed-items to every user. If a user just subscribed, old items won't be added
     *
     * @param EntryInterface $entry
     * @param Feed $feed
     * @return FeedItem|null
     */
    protected function getFeedItemForEntry(EntryInterface $entry, Feed $feed)
    {
        $content = strip_tags($entry->getDescription());
        $content = trim(str_replace('Read more...', '', $content));

        if ($this->feedItemRepository->findBy(['guid' => $entry->getId()])) {
            return null;
        }

        $feedItem = new FeedItem();
        $feedItem->setTitle($entry->getTitle());
        $feedItem->setGuid($entry->getId());
        $feedItem->setDescription(strlen($content) > 250 ? substr($content, 0, 250) . "..." : $content);
        $feedItem->setUrl($entry->getLink());
        $feedItem->setFeed($feed);

        //$users = $this->use->findOneBy(['id' => $feed->getId()]);
        $userFeeds = $this->userFeedRepository->findBy(['feed' => $feed]);

        foreach ($userFeeds as $userFeed) {
            $userFeedItem = new UserFeedItem();
            $userFeedItem->setUserFeed($userFeed);
            $userFeedItem->setUser($userFeed->getUser());
            $userFeedItem->setPinned($userFeed->isAutoPin());
            $userFeedItem->setFeedItem($feedItem);
            $userFeedItem->setViewed(false);

            $this->userFeedItemRepository->persist($userFeedItem);
        }

        return $feedItem;
    }

    /**
     * @param $url
     * @return bool|string
     */
    public function findRSSFeed($url)
    {
        $html = file_get_contents($url);
        preg_match_all('/<link\s+(.*?)\s*\/?>/si', $html, $matches);
        $links = $matches[1];
        $final_links = array();
        $link_count = count($links);
        for ($n = 0; $n < $link_count; $n++) {
            $attributes = preg_split('/\s+/s', $links[$n]);
            foreach ($attributes as $attribute) {
                $att = preg_split('/\s*=\s*/s', $attribute, 2);
                if (isset($att[1])) {
                    $att[1] = preg_replace('/([\'"]?)(.*)\1/', '$2', $att[1]);
                    $final_link[strtolower($att[0])] = $att[1];
                }
            }
            $final_links[$n] = $final_link;
        }
        #now figure out which one points to the RSS file
        for ($n = 0; $n < $link_count; $n++) {
            if (strtolower($final_links[$n]['rel']) == 'alternate') {
                if (strtolower($final_links[$n]['type']) == 'application/rss+xml') {
                    $href = $final_links[$n]['href'];
                }
                if (!$href and strtolower($final_links[$n]['type']) == 'text/xml') {
                    $href = $final_links[$n]['href'];
                }
                if ($href) {
                    if (strstr($href, "http://") !== false) {
                        $full_url = $href;
                    } elseif (substr($href, 0, 2) == '//') {
                        $full_url = 'http:'.$href;
                    } else {
                        $url_parts = parse_url($url);
                        $full_url = "http://$url_parts[host]";
                        if (isset($url_parts['port'])) {
                            $full_url .= ":$url_parts[port]";
                        }
                        if ($href{0} != '/') {
                            $full_url .= dirname($url_parts['path']);
                            if (substr($full_url, -1) != '/') {
                                $full_url .= '/';
                            }
                        }
                        $full_url .= $href;
                    }
                    return $full_url;
                }
            }
        }

        return false;
    }
}
