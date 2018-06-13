<?php

namespace App\Repository;

use App\Entity\FeedItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FeedItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeedItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeedItem[]    findAll()
 * @method FeedItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeedItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FeedItem::class);
    }


    /**
     * @param FeedItem $feedItem
     */
    public function persist(FeedItem $feedItem)
    {
        $this->createNewEntityManager();
        $this->getEntityManager()->persist($feedItem);
        $this->getEntityManager()->flush();
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     * @throws \Doctrine\ORM\ORMException
     */
    protected function createNewEntityManager()
    {
        return $this->getEntityManager()->create(
            $this->getEntityManager()->getConnection(),
            $this->getEntityManager()->getConfiguration(),
            $this->getEntityManager()->getEventManager()
        );
    }
}
