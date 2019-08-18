<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserSetting;
use App\Repository\UserSettingRepository;

class UserSettingService
{
    /**
     * @var UserSettingRepository $userSettingRepository
     */
    protected $userSettingRepository;

    /**
     * @param UserSettingRepository $userSettingRepository
     */
    public function __construct(UserSettingRepository $userSettingRepository)
    {
        $this->userSettingRepository = $userSettingRepository;
    }

    /**
     * @param User $user
     * @return UserSetting[]
     */
    public function getAllSettings(User $user)
    {
        $settingsList = [];

        $settings = $this->userSettingRepository->getAllSettings($user);

        foreach ($settings as $setting) {
           $settingsList[$setting->getSetting()] = $setting->getValue();
        }

        return $settingsList;
    }

    /**
     * @param $setting
     * @param User $user
     * @return UserSetting|null
     */
    public function getSetting($setting, User $user)
    {
        return $this->userSettingRepository->getSetting($setting, $user);
    }

    /**
     * @param UserSetting $userSetting
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persist(UserSetting $userSetting)
    {
        $this->userSettingRepository->persist($userSetting);
    }

    /**
     * @param UserSetting $userSetting
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(UserSetting $userSetting)
    {
        $this->userSettingRepository->remove($userSetting);
    }
}
