<?php

namespace App\Repository;

use App\Entity\FeedSetting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FeedSetting|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeedSetting|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeedSetting[]    findAll()
 * @method FeedSetting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeedSettingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FeedSetting::class);
    }

//    /**
//     * @return FeedSetting[] Returns an array of FeedSetting objects
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
    public function findOneBySomeField($value): ?FeedSetting
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
