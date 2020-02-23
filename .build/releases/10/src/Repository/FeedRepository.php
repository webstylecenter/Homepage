<?php

namespace App\Repository;

use App\Entity\Feed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Feed|null find($id, $lockMode = null, $lockVersion = null)
 * @method Feed|null findOneBy(array $criteria, array $orderBy = null)
 * @method Feed[]    findAll()
 * @method Feed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeedRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Feed::class);
    }

    /**
     * @param Feed $feed
     */
    public function persist(Feed $feed)
    {
        $this->getEntityManager()->persist($feed);
        $this->getEntityManager()->flush();
    }

    public function remove(Feed $feed)
    {
        $this->getEntityManager()->remove($feed);
        $this->getEntityManager()->flush();
    }
}
