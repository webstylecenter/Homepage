<?php

namespace App\Controller;

use App\Entity\Feed;
use App\Entity\FeedItem;
use App\Entity\User;
use App\Service\FeedService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SettingsController extends Controller
{
    /**
     * @Route("/settings/")
     * @return Response
     */
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $feeds = $entityManager->getRepository(Feed::class)->findBy(['user' => $this->getUser()]);

        return $this->render('settings/index.html.twig', [
            'bodyClass' => 'settings',
            'feeds' => $feeds
        ]);
    }

    /**
     * @Route("/settings/feeds/update/")
     * @param Request $request
     * @return JsonResponse
     */
    public function addAction(Request $request, FeedService $feedService)
    {
        $entityManager = $this->getDoctrine()->getManager();

        if (strlen($request->get('id')) > 0) {
            $feed = $entityManager->getRepository(Feed::class)->findOneBy(['id' => $request->get('id'), 'user' => $this->getUser()]);
        } else {
            $feed = new Feed();
        }

        $feed
            ->setColor($request->get('color', $feed->getColor()))
            ->setFeedIcon($request->get('icon', $feed->getFeedIcon()))
            ->setAutoPin(($request->get('autoPin', $feed->getAutoPin()) === 'on'))
            ->setFeedUrl($request->get('url', $feed->getFeedUrl()))
            ->setUser($this->getUser());

        if (strlen($request->get('id')) === 0) {
            $feedName = $feedService->getFeedName($feed);
            $feed->setName($feedName);
        }

        $entityManager->persist($feed);
        $entityManager->flush();

        foreach ($feedService->read($feed) as $feedItem) {
            $entityManager->persist($feedItem);
            $entityManager->flush();
        }

        return new JsonResponse([
            'id' => $feed->getId(),
            'status' => 'success'
        ]);
    }

    /**
     * @Route("/settings/feeds/remove/")
     * @param Request $request
     * @return JsonResponse
     */
    public function removeAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $feed = $entityManager->getRepository(Feed::class)->findBy(['id' => $request->get('feedId'), 'user' => $this->getUser()]);

        if (!$feed) {
            return new JsonResponse([
                'message' => 'Feed not found!',
                'status' => 'fail'
            ]);
        }

        $entityManager->remove($feed);
        $entityManager->flush();

        return new JsonResponse([
            'status' => 'success'
        ]);
    }

    /**
     * @Route("/feed/disable-xframe/")
     */
    public function disableXframeNotice()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $this->getUser()]);
        $user->setHideXframeNotice(true);
        $entityManager->persist($user);
        $entityManager->flush();

        echo 'Message disabled';
        exit;
    }
}