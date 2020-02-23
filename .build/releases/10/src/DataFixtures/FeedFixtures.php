<?php

namespace App\DataFixtures;

use App\Entity\Feed;
use App\Entity\FeedItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class FeedFixtures extends Fixture
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    public function load(ObjectManager $manager)
    {
        $this->objectManager = $manager;

        $feed = new Feed();
        $feed->setName('Eindhovens Dagblad');
        $feed->setUrl('http://www.ed.nl/deurne-e-o/rss.xml');
        $this->populateFeed($feed, 'feed_ed');
        $this->objectManager->persist($feed);
        $this->setReference('feed_ed', $feed);

        $feed = new Feed();
        $feed->setName('NOS');
        $feed->setUrl('http://feeds.nos.nl/nosnieuwsalgemeen');
        $this->populateFeed($feed, 'feed_nos');
        $this->objectManager->persist($feed);
        $this->setReference('feed_nos', $feed);

        $manager->flush();
    }

    /**
     * @param Feed $feed
     * @param string $reference
     */
    public function populateFeed(Feed $feed, $reference)
    {
        $faker = \Faker\Factory::create('NL-nl');

        for ($i = 1; $i <= 25; $i++) {
            $feedItem = new FeedItem();
            $feedItem->setFeed($feed);
            $feedItem->setUrl($faker->url);
            $feedItem->setTitle($faker->text(75));
            $feedItem->setDescription($faker->text(255));
            $feedItem->setGuid($feedItem->getUrl());

            $feed->getItems()->add($feedItem);
            $this->setReference($reference . '_' . $i, $feedItem);
        }
    }
}
