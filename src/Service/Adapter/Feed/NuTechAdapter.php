<?php

namespace Service\Adapter\Feed;

/**
 * Class NuTechAdapter
 * @package Service\Adapter\Feed
 */
class NuTechAdapter extends AbstractFeedAdapter
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'NuTech';
    }

    /**
     * @return string
     */
    public function getFeedUrl()
    {
        return 'http://www.nu.nl/rss/tech';
    }
}
