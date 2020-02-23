<?php

namespace App\Service;

use App\Entity\ChecklistItem;
use App\Entity\User;
use App\Repository\ChecklistItemRepository;

class ChecklistService
{
    /**
     * @var ChecklistItemRepository
     */
    protected $checklistItemRepository;

    /**
     * @param ChecklistItemRepository $checklistItemRepository
     */
    public function __construct(ChecklistItemRepository $checklistItemRepository)
    {
        $this->checklistItemRepository = $checklistItemRepository;
    }

    /**
     * @param User $user
     * @return ChecklistItem[]
     */
    public function getUncheckedItemsForUser(User $user)
    {
        return $this
            ->checklistItemRepository
            ->getUncheckedItemsForUser($user);
    }

    /**
     * @param User $user
     * @return ChecklistItem[]
     */
    public function getCheckedItemsForUser(User $user)
    {
        return $this
            ->checklistItemRepository
            ->getCheckedItemsForUser($user);
    }
}
