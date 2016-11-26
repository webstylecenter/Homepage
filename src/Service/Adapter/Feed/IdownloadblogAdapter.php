<?php

namespace Service\Adapter\Feed;

/**
 * Class IdownloadblogAdapter
 * @package Service\Adapter\Feed
 */
class IdownloadblogAdapter extends AbstractFeedAdapter
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'iDownloadBlog';
    }

    /**
     * @return string
     */
    public function getFeedUrl()
    {
        return 'https://www.idownloadblog.com/feed/';
    }
}
