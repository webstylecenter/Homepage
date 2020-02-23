<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeedbackController extends AbstractController
{
    /**
     * @Route("/feedback/")
     * @return Response
     */
    public function index()
    {
        return $this->render('feedback/index.html.twig');
    }
}
