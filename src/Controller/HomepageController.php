<?php

namespace App\Controller;

use App\Service\WeatherService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomepageController extends Controller
{
    /**
     * @Route("/")
     * @param WeatherService $weatherService
     * @return Response
     * @throws \Exception
     */
    public function index(WeatherService $weatherService)
    {
        // Setting temporally values
        $bodyClass = 'Homepage';

        return $this->render('home/index.html.twig', [
            'bodyClass' => $bodyClass,
            'forecast' => $weatherService->getForecastList(),
            'feedItems'=> [],
            'feeds' => [],
            'device' => 'desktop',
            'nextPageNumber' => 2,
        ]);
    }
}