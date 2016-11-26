<?php

namespace Service\Adapter\Feed;

/**
 * Class GeenstijlAdapter
 * @package Service\Adapter\Feed
 */
class GeenstijlAdapter extends AbstractFeedAdapter
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'GeenStijl';
    }

    /**
     * @return string
     */
    public function getFeedUrl()
    {
        return 'http://feeds.feedburner.com/geenstijl/';
    }
}
