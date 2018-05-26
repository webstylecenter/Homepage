<?php

namespace App\DataFixtures;

use App\Entity\UserFeed;
use App\Entity\UserFeedItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class UserFeedFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    public function load(ObjectManager $manager)
    {
        $this->objectManager = $manager;

        $userFeed = new UserFeed;
        $userFeed->setFeed($this->getReference('feed_nos'));
        $userFeed->setUser($this->getReference('user_1'));
        $userFeed->setColor('#c3c3c3');
        $userFeed->setIcon('video');
        $this->populateItems($userFeed, 'feed_nos');

        $this->objectManager->persist($userFeed);

        $userFeed = new UserFeed;
        $userFeed->setFeed($this->getReference('feed_ed'));
        $userFeed->setUser($this->getReference('user_1'));
        $userFeed->setColor('#d6d6d6');
        $userFeed->setIcon('trash');
        $this->populateItems($userFeed, 'feed_ed');

        $this->objectManager->persist($userFeed);

        $this->objectManager->flush();
    }

    /**
     * @param UserFeed $userFeed
     * @param string $reference
     */
    public function populateItems(UserFeed $userFeed, $reference)
    {
        for ($i = 1; $i <= 25; $i++) {
            $userFeedItem = new UserFeedItem;
            $userFeedItem->setPinned(rand(1, 9) % 9 === 0);
            $userFeedItem->setViewed(rand(1, 25) % 3);
            $userFeedItem->setUser($userFeed->getUser());
            $userFeedItem->setUserFeed($userFeed);
            $userFeedItem->setFeedItem($this->getReference($reference . '_' . $i));
            $userFeed->getItems()->add($userFeedItem);
        }
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            FeedFixtures::class,
            UserFixtures::class,
        ];
    }
}
