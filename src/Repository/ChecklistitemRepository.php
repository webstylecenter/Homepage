<?php

namespace App\Repository;

use App\Entity\Checklistitem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Checklistitem|null find($id, $lockMode = null, $lockVersion = null)
 * @method Checklistitem|null findOneBy(array $criteria, array $orderBy = null)
 * @method Checklistitem[]    findAll()
 * @method Checklistitem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChecklistitemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Checklistitem::class);
    }

//    /**
//     * @return Checklistitem[] Returns an array of Checklistitem objects
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
    public function findOneBySomeField($value): ?Checklistitem
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
