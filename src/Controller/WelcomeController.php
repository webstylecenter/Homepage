<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WelcomeController extends Controller
{
    /**
     * @Route("/welcome/")
     */
    public function index()
    {

        return $this->render('welcome/index.html.twig', [
            'bodyClass' => 'welcome',
           // 'forecast' => $weatherService->getForecastList(),
            'notes'=> [],
            'todos' => []
        ]);
    }
}