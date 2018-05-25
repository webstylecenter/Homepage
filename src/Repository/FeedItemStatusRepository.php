<?php

namespace App\Repository;

use App\Entity\FeedItemStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FeedItemStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeedItemStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeedItemStatus[]    findAll()
 * @method FeedItemStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeedItemStatusRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FeedItemStatus::class);
    }

//    /**
//     * @return FeedItemStatus[] Returns an array of FeedItemStatus objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FeedItemStatus
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
