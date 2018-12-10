<?php

namespace App\Controller;

use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    /**
     * @Route("/weather/{type}/")
     * @param $type
     * @param WeatherService $weatherService
     * @return Response
     * @throws \Exception
     */
    public function index($type, WeatherService $weatherService)
    {
        return $this->render('weather/' . $type . '.html.twig', [
            'bodyClass' => 'WeatherMobilePage',
            'forecast' => $weatherService->getForecastList()
        ]);
    }
}
