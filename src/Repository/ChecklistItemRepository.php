<?php

namespace App\Repository;

use App\Entity\ChecklistItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ChecklistItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChecklistItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChecklistItem[]    findAll()
 * @method ChecklistItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChecklistItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ChecklistItem::class);
    }

//    /**
//     * @return ChecklistItem[] Returns an array of ChecklistItem objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ChecklistItem
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
