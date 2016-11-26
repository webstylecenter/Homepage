<?php

namespace Service\Adapter\Feed;

/**
 * Class ArtiestennieuwsAdapter
 * @package Service\Adapter\Feed
 */
class ArtiestennieuwsAdapter extends AbstractFeedAdapter
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Artiesten Nieuws';
    }

    /**
     * @return string
     */
    public function getFeedUrl()
    {
        return 'http://www.artiestennieuws.nl/feed/';
    }
}

