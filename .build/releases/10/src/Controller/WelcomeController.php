<?php

namespace App\Controller;

use App\Service\ChecklistService;
use App\Service\NoteService;
use Mobile_Detect;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController extends AbstractController
{
    /**
     * @var ChecklistService
     */
    protected $checklistService;

    /**
     * @var NoteService
     */
    protected $noteService;

    /**
     * @param ChecklistService $checklistService
     * @param NoteService $noteService
     */
    public function __construct(ChecklistService $checklistService, NoteService $noteService)
    {
        $this->checklistService = $checklistService;
        $this->noteService = $noteService;
    }

    /**
     * @Route("/welcome/")
     * @return Response
     */
    public function index()
    {
        $device = new Mobile_Detect();

        return $this->render('welcome/index.html.twig', [
            'bodyClass' => 'welcome',
            'isMobile' => $device->isMobile(),
            'notes' => $this->noteService->getForUser($this->getUser()),
            'todos' => $this->checklistService->getUncheckedItemsForUser($this->getUser())
        ]);
    }
}
