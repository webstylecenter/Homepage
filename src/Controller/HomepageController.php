<?php

namespace App\Controller;

use App\Entity\Feed;
use App\Entity\FeedItem;
use App\Service\WeatherService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Detection\MobileDetect;

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

        /** @var $device */
        $device = new MobileDetect();

        $entityManager = $this->getDoctrine()->getManager();

        return $this->render('home/index.html.twig', [
            'bodyClass' => $bodyClass,
            'forecast' => $weatherService->getForecastList(),
            'feedItems'=> $entityManager->getRepository(FeedItem::class)->findBy([], ['pinned' => 'DESC', 'createdAt' => 'DESC'], 50),
            'feeds' => $entityManager->getRepository(Feed::class)->findAll(),
            'device' => $device,
            'nextPageNumber' => 2,
        ]);
    }

    /**
     * @Route("/offline/")
     * @return Response
     */
    public function offlinePage()
    {
        return $this->render('home/offline.html.twig', [
            'bodyClass' => 'offline'
        ]);
    }
}