<?php

namespace App\Controller;

use App\Entity\ChecklistItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ChecklistController extends Controller
{
    /**
     * @Route("/checklist/")
     * @return Response
     */
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();

        return $this->render('checklist/index.html.twig', [
            'bodyClass' => 'checklist',
            'todos' => $entityManager->getRepository(ChecklistItem::class)->findBy(['checked'=>false], ['updatedAt'=> 'DESC']),
            'finished' => $entityManager->getRepository(ChecklistItem::class)->findBy(['checked'=>true], ['updatedAt'=> 'DESC'], 50)
        ]);
    }

    /**
     * @Route("/checklist/add/")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function addAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        if (strlen($request->get('id')) > 0) {
            $checklistItem = $entityManager->getRepository(ChecklistItem::class)->find($request->get('id'));
            $checklistItem->setChecked($request->get('checked') === 'true');
        } else {
            $checklistItem = new checklistItem();
            $checklistItem
                ->setItem($request->get('item'))
                ->setChecked(false);
        }

        $entityManager->persist($checklistItem);
        $entityManager->flush();

        return $this->render('checklist/checklist.html.twig', [
            'todos' => $entityManager->getRepository(ChecklistItem::class)->findBy(['checked'=>false], ['updatedAt'=> 'DESC']),
            'finished' => $entityManager->getRepository(ChecklistItem::class)->findBy(['checked'=>true], ['updatedAt'=> 'DESC'], 50)
        ]);
    }
}