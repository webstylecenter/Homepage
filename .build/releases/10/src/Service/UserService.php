<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserService
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var UserRepository;
     */
    protected $userRepository;

    public function __construct(TokenStorageInterface $tokenStorage, UserRepository $userRepository)
    {
        $this->tokenStorage = $tokenStorage;
        $this->userRepository = $userRepository;
    }

    /**
     * @return User|null
     */
    public function getCurrentUser()
    {
        return $this->tokenStorage->getToken()->getUser() ?? null;
    }

    /**
     * @return User[]
     */
    public function getAllUsers()
    {
        return $this->userRepository->findAll();
    }
}
