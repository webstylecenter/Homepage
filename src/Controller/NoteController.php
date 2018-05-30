<?php

namespace App\Controller;

use App\Entity\Note;
use App\Service\NoteService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NoteController extends Controller
{
    /**
     * @var NoteService
     */
    protected $noteService;

    /**
     * @param NoteService $noteService
     */
    public function __construct(NoteService $noteService)
    {
        $this->noteService = $noteService;
    }

    /**
     * @Route("/note/save/")
     * @param Request $request
     * @return Response
     */
    public function saveAction(Request $request)
    {
        $note = $this->noteService->getByIdAndUser($request->get('id'), $this->getUser()) ?: new Note;
        $note->setUser($this->getUser());
        $note->setName($request->get('name'));
        $note->setContent($request->get('note'));
        $note->setPosition((int) $request->get('position', 0));

        $this->noteService->persist($note);

        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'id' => $note->getId()
            ]
        ]);
    }

    /**
     * @Route("/note/remove/")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function removeAction(Request $request)
    {
        $note = $this->noteService->getByIdAndUser($request->get('id'), $this->getUser());
        $this->noteService->remove($note);

        return new JsonResponse([
            'status' => 'success',
        ]);
    }
}