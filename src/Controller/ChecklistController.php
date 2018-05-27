<?php

namespace App\Controller;

use App\Entity\ChecklistItem;
use App\Entity\UserFeedItem;
use App\Service\ChecklistService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ChecklistController extends Controller
{
    /**
     * @var ChecklistService
     */
    protected $checklistService;

    /**
     * @param ChecklistService $checklistService
     */
    public function __construct(ChecklistService $checklistService)
    {
        $this->checklistService = $checklistService;
    }

    /**
     * @Route("/checklist/")
     * @return Response
     */
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();

        return $this->render('checklist/index.html.twig', [
            'bodyClass' => 'checklist',
            'todos' => $entityManager->getRepository(ChecklistItem::class)->findBy(['checked' => false, 'user' => $this->getUser()], ['updatedAt' => 'DESC']),
            'finished' => $entityManager->getRepository(ChecklistItem::class)->findBy(['checked' => true, 'user' => $this->getUser()], ['updatedAt' => 'DESC'], 50)
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

        $checklistItem = $entityManager->getRepository(ChecklistItem::class)->findOneBy(['id' => $request->get('id'), 'user' => $this->getUser()]) ?: new checklistItem;
        $checklistItem->setChecked($request->get('checked') === 'true');
        $checklistItem->setItem($request->get('item', $checklistItem->getItem()));
        $checklistItem->setUser($this->getUser());

        $entityManager->persist($checklistItem);
        $entityManager->flush();

        return $this->render('checklist/checklist.html.twig', [
            'todos' => $this->checklistService->getUncheckedItemsForUser($this->getUser()),
            'finished' => $this->checklistService->getCheckedItemsForUser($this->getUser())
        ]);
    }
}