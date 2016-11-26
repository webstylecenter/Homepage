<?php

namespace Service\Adapter\Feed;

/**
 * Class NOSnieuwsAdapter
 * @package Service\Adapter\Feed
 */
class NOSnieuwsAdapter extends AbstractFeedAdapter
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'NOS';
    }

    /**
     * @return string
     */
    public function getFeedUrl()
    {
        return 'http://feeds.nos.nl/nosnieuwsalgemeen';
    }
}
