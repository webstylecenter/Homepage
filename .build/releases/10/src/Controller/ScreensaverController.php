<?php

namespace App\Controller;

use App\Entity\FeedItem;
use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScreensaverController extends AbstractController
{
    /**
     * @Route("/screensaver/")
     * @param WeatherService $weatherService
     * @return Response
     */
    public function indexAction(WeatherService $weatherService)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $feedItems = $entityManager->getRepository(FeedItem::class)->findBy([], ['createdAt' => 'DESC'], 30);

        return $this->render('screensaver/index.html.twig', [
            'bodyClass' => 'screensaver',
            'forecast' => $weatherService,
            'feedItems' => $feedItems
        ]);
    }

    /**
     * @Route("/screensaver/images/{file}.jpg")
     * @return RedirectResponse
     */
    public function backgroundImageAction()
    {
        if (rand(1, 5) === 1) {
            return new RedirectResponse($_SERVER['WALLPAPER_LOCATION_PRIVATE']);
        }
        return new RedirectResponse($_SERVER['WALLPAPER_LOCATION_PUBLIC']);
    }

}
