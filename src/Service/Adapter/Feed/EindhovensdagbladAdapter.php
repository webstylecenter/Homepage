<?php

namespace Service\Adapter\Feed;

/**
 * Class EindhovensdagbladAdapter
 * @package Service\Adapter\Feed
 */
class EindhovensdagbladAdapter extends AbstractFeedAdapter
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'EindhovensDagblad';
    }

    /**
     * @return string
     */
    public function getFeedUrl()
    {
        return 'http://www.ed.nl/deurne-e-o/rss.xml';
    }
}

