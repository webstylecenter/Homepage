<?php

namespace App\Controller;

use App\Entity\Feed;
use App\Entity\FeedItem;
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
            'feeds'=> $feeds
        ]);
    }

    /**
     * @Route("/settings/feeds/update/")
     * @param Request $request
     * @return JsonResponse
     */
    public function addAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        if (strlen($request->get('id')) > 0) {
            $feed = $entityManager->getRepository(Feed::class)->findBy(['id' => $request->get('id'), 'user'=>$this->getUser()]);
            $feed->setName($request->get('name', $feed->getName()))
                ->setColor($request->get('color', $feed->getColor()))
                ->setFeedIcon($request->get('icon', $feed->getFeedIcon()))
                ->setAutoPin(($request->get('autoPin', $feed->getAutoPin()) === 'on'))
                ->setFeedUrl($request->get('url', $feed->getFeedUrl()));
        } else {
            $feed = new Feed();
            $feed->setName($request->get('name'))
                ->setColor($request->get('color'))
                ->setFeedIcon($request->get('icon', null))
                ->setAutoPin(($request->get('autoPin') === 'on'))
                ->setFeedUrl($request->get('url'))
                ->setUser($this->getUser());
        }

        $entityManager->persist($feed);
        $entityManager->flush();

        return new JsonResponse([
            'id'=> $feed->getId(),
            'status'=> 'success'
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
        $feed = $entityManager->getRepository(Feed::class)->findBy(['id' => $request->get('feedId'), 'user'=>$this->getUser()]);

        if (!$feed) {
            return new JsonResponse([
                'message'=> 'Feed not found!',
                'status'=> 'fail'
            ]);
        }

        $entityManager->remove($feed);
        $entityManager->flush();

        return new JsonResponse([
            'status'=> 'success'
        ]);
    }
}