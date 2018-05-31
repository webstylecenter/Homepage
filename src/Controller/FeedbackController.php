<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FeedbackController extends Controller
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