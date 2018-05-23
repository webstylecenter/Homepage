<?php

namespace App\Controller;

use App\Entity\Feed;
use App\Entity\FeedItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FeedController extends Controller
{
    /**
     * @Route("/feed/add-item/")
     * @return JsonResponse
     */
    public function addFeedAction(Request $request)
    {
        // TODO: Probably not working yet, no "user" feed made yet
        $entityManager = $this->getDoctrine()->getManager();

        $feed = $entityManager->getRepository(Feed::class)->findOneBy(['feedUrl'=>'']);

        $feedItem = new FeedItem();
        $feedItem
            ->setFeed($feed)
            ->setViewed(false)
            ->setGuid(intval(time()))
            ->setTitle($request->get('title'))
            ->setDescription($request->get('description', null))
            ->setPinned(true)
            ->setUrl($request->get('url'));

        $entityManager->persist($feedItem);
        $entityManager->flush();

        return new JsonResponse([
            'status'=> 'success',
            'message' => 'Item added'
        ]);
    }

    /**
     * @Route("/feed/pin/{id}")
     * @param $id
     * @return JsonResponse
     */
    public function pinAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $feedItem = $entityManager->getRepository(FeedItem::class)->find(['id'=> $id]);
        $feedItem->setPinned((!$feedItem->getPinned()));

        $entityManager->persist($feedItem);
        $entityManager->flush();

        return new JsonResponse([
            'status'=> 'success',
            'message' => 'Pin toggled'
        ]);
    }

    /**
     * @Route("/feed/refresh/")
     * @return JsonResponse
     */
    public function refreshAction()
    {
        // TODO: Currently broken
        $entityManager = $this->getDoctrine()->getManager();
        return new JsonResponse([
            'html' => $this->render('home/newsfeed.html.twig', [
                'feedItems'=> $entityManager->getRepository(FeedItem::class)->findBy(['viewed'=>false], ['createdAt' => 'DESC'], 50),
                'nextPageNumber' => 50000,
                'addToChecklist' => '',
            ]),
            'refreshDate' => (new \DateTime())->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @Route("/feed/page/{startIndex}")
     * @param $startIndex
     * @return Response
     */
    public function loadExtraAction($startIndex)
    {
        $entityManager = $this->getDoctrine()->getManager();
        return $this->render('home/newsfeed.html.twig', [
            'feedItems'=> $entityManager->getRepository(FeedItem::class)->findBy([], ['createdAt' => 'DESC'], 50, ($startIndex-1)*50),
            'nextPageNumber' => $startIndex + 1,
            'addToChecklist' => '',
        ]);
    }
}