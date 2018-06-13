<?php

namespace App\Repository;

use App\Entity\FeedListFilter;
use App\Entity\User;
use App\Entity\UserFeedItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserFeedItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserFeedItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserFeedItem[]    findAll()
 * @method UserFeedItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserFeedItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserFeedItem::class);
    }

    /**
     * @param User $user
     */
    public function setViewedForUser(User $user)
    {
        $this->createQueryBuilder('ufi')->update()
            ->set('ufi.viewed', 1)
            ->where('ufi.user = :user')
            ->setParameter('user', $user->getId())
            ->getQuery()
            ->execute();
    }

    /**
     * @param UserFeedItem $userFeedItem
     */
    public function persist(UserFeedItem $userFeedItem)
    {
        try {
            $this->getEntityManager()->persist($userFeedItem);
            $this->getEntityManager()->flush();
        } catch (\Exception $e) {
            echo '### Message ### \n'.$e->getMessage().'\n### Trace ### \n'.$e->getTraceAsString() . PHP_EOL;
        }

    }

    /**
     * @param FeedListFilter $feedListFilter
     * @return UserFeedItem[]
     */
    public function getWithFilter(FeedListFilter $feedListFilter)
    {
        $queryBuilder = $this->createQueryBuilder('ufi')
            ->select()
            ->orderBy('ufi.pinned', 'DESC')
            ->addOrderBy('ufi.createdAt', 'DESC')
            ->setFirstResult($feedListFilter->getIndex() * $feedListFilter->getLimit())
            ->setMaxResults($feedListFilter->getLimit());

        if ($feedListFilter->getUser()) {
            $queryBuilder
                ->where('ufi.user = :user')
                ->setParameter('user', $feedListFilter->getUser()->getId());
        }

        if ($feedListFilter->getSearchQuery()) {
            $this->appendSearchQuery($queryBuilder, $feedListFilter->getSearchQuery());
        }

        if ($feedListFilter->isNewOnly()) {
            $queryBuilder->andWhere('ufi.viewed = 0');
        }

        return $queryBuilder->getQuery()->execute();
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $searchQuery
     */
    protected function appendSearchQuery(QueryBuilder &$queryBuilder, $searchQuery)
    {
        $queryBuilder->setParameter('query', '%' . $searchQuery . '%');
        $queryBuilder->leftJoin('ufi.feedItem', 'ufifi');
        $queryBuilder->andWhere(
            $queryBuilder->expr()->orX(
                $queryBuilder->expr()->like('ufifi.title', ':query'),
                $queryBuilder->expr()->like('ufifi.description', ':query')
            )
        );
    }
}
