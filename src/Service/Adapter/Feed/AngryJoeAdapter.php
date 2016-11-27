<?php

namespace Service\Adapter\Feed;

/**
 * Class AngryJoeAdapter
 * @package Service\Adapter\Feed
 */
class AngryJoeAdapter extends AbstractFeedAdapter
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'AngryJoe';
    }

    /**
     * @return string
     */
    public function getFeedUrl()
    {
        return 'http://angryjoeshow.com/feed/';
    }
}

