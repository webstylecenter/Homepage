<?php

namespace App\Repository;

use App\Entity\UserSetting;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserSetting|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserSetting|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserSetting[]    findAll()
 * @method UserSetting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserSettingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserSetting::class);
    }

    /**
     * @param User $user
     * @return UserSetting[]
     */
    public function getAllSettings(User $user)
    {
        return $this->findBy(['user' => $user]);
    }

    /**
     * @param $setting
     * @param User $user
     * @return UserSetting|null
     */
    public function getSetting($setting, User $user)
    {
        return $this->findOneBy(['setting' => $setting, 'user' => $user]);
    }

    /**
     * @param UserSetting $userSetting
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persist(UserSetting $userSetting)
    {
        $this->getEntityManager()->persist($userSetting);
        $this->getEntityManager()->flush();
    }

    /**
     * @param UserSetting $userSetting
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(UserSetting $userSetting)
    {
        $this->getEntityManager()->remove($userSetting);
        $this->getEntityManager()->flush();
    }
}
