<?php

namespace Service\Adapter\Feed;

class DumpertAdapter extends AbstractFeedAdapter
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Dumpert';
    }

    /**
     * @return string
     */
    public function getFeedUrl()
    {
        return 'http://www.dumpert.nl/rss.xml.php';
    }
}

