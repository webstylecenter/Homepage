<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomepageController extends Controller
{
    /**
    * @Route("/")
    */
    public function index()
    {
        // Setting temporally values
        $bodyClass = 'Homepage';

        return $this->render('home/index.html.twig', [
            'bodyClass' => $bodyClass,
            'forecast' => '',
            'feedItems'=> [],
            'feeds' => [],
            'device' => 'desktop',
            'nextPageNumber' => 2,
        ]);
    }
}