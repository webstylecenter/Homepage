<?php

namespace Service\Adapter\Feed;

/**
 * Class NeowinAdapter
 * @package Service\Adapter\Feed
 */
class NeowinAdapter extends AbstractFeedAdapter
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Neowin';
    }

    /**
     * @return string
     */
    public function getFeedUrl()
    {
        return 'http://feeds.feedburner.com/neowin-main';
    }
}
