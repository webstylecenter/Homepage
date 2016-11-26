<?php

namespace Service\Adapter\Feed;

/**
 * Class MajorNelsonAdapter
 * @package Service\Adapter\Feed
 */
class MajorNelsonAdapter extends AbstractFeedAdapter
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'MajorNelson';
    }

    /**
     * @return string
     */
    public function getFeedUrl()
    {
        return 'http://feeds.feedburner.com/MajorNelson';
    }
}
