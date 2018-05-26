<?php

namespace App\Controller;

use App\Entity\Feed;
use App\Entity\FeedItem;
use App\Entity\User;
use App\Service\FeedService;
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
     * @param FeedService $feedService
     * @return Response
     * @throws \Exception
     */
    public function index(WeatherService $weatherService, FeedService $feedService)
    {
        // Setting temporally values
        $bodyClass = 'Homepage';

        /** @var $device */
        $device = new MobileDetect();

        $entityManager = $this->getDoctrine()->getManager();
        $feedItems = $entityManager->getRepository(FeedItem::class)->findBy([
                'user'=>$this->getUser()
            ], [
                'pinned' => 'DESC', 'createdAt' => 'DESC'
            ], 50
        );

        $user = $entityManager->getRepository(User::class)->findOneBy(['id'=> $this->getUser()]);

        $feedService->markAllViewed($user);

        return $this->render('home/index.html.twig', [
            'bodyClass' => $bodyClass,
            'forecast' => $weatherService->getForecastList(),
            'feedItems'=> $feedItems,
            'feeds' => $entityManager->getRepository(Feed::class)->findAll(),
            'device' => $device,
            'user' => $user,
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