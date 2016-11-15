<?php

namespace Service\Adapter\Feed;

class GamersnetAdapter extends AbstractFeedAdapter
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Gamersnet';
    }

    /**
     * @return string
     */
    public function getFeedUrl()
    {
        return 'https://www.gamersnet.nl/rssnews.xml';
    }
}
