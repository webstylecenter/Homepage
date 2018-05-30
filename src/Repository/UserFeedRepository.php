<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserFeed;
use App\Entity\UserFeedItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserFeed|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserFeed|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserFeed[]    findAll()
 * @method UserFeed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserFeedRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserFeed::class);
    }

    /**
     * @param User $user
     * @return UserFeed[]
     */
    public function getAllForUser(User $user)
    {
        return $this->findBy(['user' => $user]);
    }

    /**
     * @param UserFeed $userFeed
     */
    public function persist(UserFeed $userFeed)
    {
        $this->getEntityManager()->persist($userFeed);
        $this->getEntityManager()->flush();
    }

    /**
     * @param UserFeed $userFeed
     */
    public function remove(UserFeed $userFeed)
    {
        $this->getEntityManager()->remove($userFeed);
        $this->getEntityManager()->flush();
    }
}
