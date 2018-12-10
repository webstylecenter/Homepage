<?php

namespace App\Controller;

use App\Entity\FeedListFilter;
use App\Service\FeedService;
use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Detection\MobileDetect;

class HomepageController extends AbstractController
{
    /**
     * @var FeedService
     */
    protected $feedService;

    /**
     * @var WeatherService
     */
    protected $weatherService;

    /**
     * @param FeedService $feedService
     * @param WeatherService $weatherService
     */
    public function __construct(FeedService $feedService, WeatherService $weatherService)
    {
        $this->feedService = $feedService;
        $this->weatherService = $weatherService;
    }

    /**
     * @Route("/")
     * @return Response
     * @throws \Exception
     */
    public function index()
    {
        $userFeedItems = $this->feedService->getUserFeedItemsWithFilter((new FeedListFilter())
            ->setUser($this->getUser())
        );

        $this->feedService->setViewedForUser($this->getUser());

        return $this->render('home/index.html.twig', [
            'bodyClass' => 'Homepage',
            'forecast' => $this->weatherService->getForecastList(),
            'userFeedItems' => $userFeedItems,
            'device' => new MobileDetect(),
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
