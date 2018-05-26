<?php

namespace App\Service;

use App\Entity\Note;
use App\Entity\User;
use App\Repository\NoteRepository;

class NoteService
{
    /**
     * @var NoteRepository
     */
    protected $noteRepository;

    /**
     * @param NoteRepository $noteRepository
     */
    public function __construct(NoteRepository $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    /**
     * @param User $user
     * @return Note[]
     */
    public function getForUser(User $user)
    {
        return $this->noteRepository->getForUser($user);
    }

    /**
     * @param integer $id
     * @param User $user
     * @return Note
     */
    public function getByIdAndUser($id, User $user)
    {
        return $this->noteRepository->getByIdAndUser($id, $user);
    }

    /**
     * @param Note $note
     */
    public function persist(Note $note)
    {
        $this->noteRepository->persist($note);
    }

    /**
     * @param Note $note
     */
    public function remove(Note $note)
    {
        $this->noteRepository->remove($note);
    }
}
