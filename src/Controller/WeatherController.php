<?php

namespace App\Controller;

use App\Service\WeatherService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WeatherController extends Controller
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