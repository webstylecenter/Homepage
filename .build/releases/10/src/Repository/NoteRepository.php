<?php

namespace App\Repository;

use App\Entity\Note;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Note|null find($id, $lockMode = null, $lockVersion = null)
 * @method Note|null findOneBy(array $criteria, array $orderBy = null)
 * @method Note[]    findAll()
 * @method Note[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Note::class);
    }

    /**
     * @param User $user
     * @return Note[]
     */
    public function getForUser(User $user)
    {
        return $this->findBy(['user' => $user]);
    }

    /**
     * @param integer $id
     * @param User $user
     * @return Note
     */
    public function getByIdAndUser($id, User $user)
    {
        return $this->findOneBy(['id' => $id, 'user' => $user]);
    }

    /**
     * @param Note $note
     */
    public function persist(Note $note)
    {
        $this->getEntityManager()->persist($note);
        $this->getEntityManager()->flush();
    }

    /**
     * @param Note $note
     */
    public function remove(Note $note)
    {
        $this->getEntityManager()->remove($note);
        $this->getEntityManager()->flush();
    }
}
