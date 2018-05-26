<?php

namespace App\Controller;

use App\Entity\Note;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NoteController extends Controller
{
    /**
     * @Route("/note/save/")
     * @param Request $request
     * @return Response
     */
    public function addAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $note = $entityManager->getRepository(Note::class)->findBy(['id' => $request->get('id'), 'user' => $this->getUser()]);

        if (!$note) {
            $note = new Note();
            $note->setUser($this->getUser());
        }

        $note->setName($request->get('name', ''))
            ->setContent($request->get('note', ''))
            ->setPosition((int)$request->get('position', null));

        $entityManager->persist($note);
        $entityManager->flush();

        return new JsonResponse([
            'id' => $note->getId()
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
        $entityManager = $this->getDoctrine()->getManager();
        $note = $entityManager->getRepository(Note::class)->findBy(['id' => $request->get('id'), 'user' => $this->getUser()]);

        if (!$note) {
            return new JsonResponse([
                'message' => 'Note not found!',
                'status' => 'fail'
            ]);
        }

        $entityManager->remove($note);
        $entityManager->flush();

        return new JsonResponse([
            'status' => 'success'
        ]);
    }
}