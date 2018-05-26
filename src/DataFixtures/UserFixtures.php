<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserManagerInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    public function load(ObjectManager $manager)
    {
        $this->objectManager = $manager;

        $user = $this->userManager->createUser();
        $user->setUsername('peter');
        $user->setEmail('peter@petervdam.nl');
        $user->setPlainPassword('testtest');
        $user->setEnabled(true);

        $this->userManager->updateUser($user);
        $this->setReference('user_1', $user);
        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            FeedFixtures::class,
        ];
    }
}
