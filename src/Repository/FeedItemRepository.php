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

    public function findByUser($user)
    {
//        return $this->createQueryBuilder('user')
//            ->from('App:FeedItem');

//        return $this->createQueryBuilder('feed_item')
//            ->join('feed_item.feed', 'feed', 'ON', 'feed.id = feed_item.feed')
//            ->getQuery();

        return $this->createQueryBuilder('feed_item')
            ->getQuery()
            ->execute();
//            ->where('f.title LIKE :query')
//            ->orWhere('f.description LIKE :query')
//            ->andWhere('f.user = :user')
//            ->setParameter('query', '%'.$request->get('query').'%')
//            ->setParameter('user', $this->getUser())
//            ->orderBy('f.pinned', 'DESC')
//            ->orderBy('f.createdAt', 'DESC')
//            ->getQuery()->getResult();
    }

//    /**
//     * @return FeedItem[] Returns an array of FeedItem objects
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
    public function findOneBySomeField($value): ?FeedItem
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
