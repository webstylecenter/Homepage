<?php

namespace App\Controller;

use App\Entity\Feed;
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

        $feeds = $entityManager->getRepository(Feed::class)->findAll();

        return $this->render('settings/index.html.twig', [
            'bodyClass' => 'settings',
            'feeds'=> $feeds
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function addAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $feed = $entityManager->getRepository(Feed::class)->find($request->get('id'));

        if (!$feed) {
            $feed = new Feed();
        }

        $feed->setName($request->get('name'))
            ->setColor($request->get('color'))
            ->setFeedIcon($request->get('icon', null))
            ->setAutoPin($request->get('autoPin', false))
            ->setFeedUrl($request->get('url'));

        $entityManager->persist($feed);
        $entityManager->flush();

        return new JsonResponse([
            'id'=> $feed->getId(),
            'status'=> 'success'
        ]);
    }
}