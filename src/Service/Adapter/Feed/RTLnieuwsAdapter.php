<?php

namespace Service\Adapter\Feed;

/**
 * Class RTLnieuwsAdapter
 * @package Service\Adapter\Feed
 */
class RTLnieuwsAdapter extends AbstractFeedAdapter
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'RTLnieuws';
    }

    /**
     * @return string
     */
    public function getFeedUrl()
    {
        return 'http://www.rtlnieuws.nl/service/rss/nederland/index.xml';
    }
}
