<?php

namespace App\Controller;

use App\Entity\ChecklistItem;
use App\Entity\Note;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WelcomeController extends Controller
{
    /**
     * @Route("/welcome/")
     * @return Response
     */
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $notes = $entityManager->getRepository(Note::class)->findAll();
        $checklistItems = $entityManager->getRepository(ChecklistItem::class)->findBy(['checked'=>false], ['updatedAt'=> 'DESC']);

        return $this->render('welcome/index.html.twig', [
            'bodyClass' => 'welcome',
           // 'forecast' => $weatherService->getForecastList(),
            'notes'=> $notes,
            'todos' => $checklistItems
        ]);
    }
}