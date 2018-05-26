<?php

namespace App\Repository;

use App\Entity\ChecklistItem;
use App\Entity\User;
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

    /**
     * @param User $user
     * @return ChecklistItem[]
     */
    public function getUncheckedItemsForUser(User $user)
    {
        return $this->findBy(['checked' => false, 'user' => $user]);
    }
}
