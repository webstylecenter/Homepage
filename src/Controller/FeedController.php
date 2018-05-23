<?php

namespace App\Controller;

use App\Entity\Feed;
use App\Entity\FeedItem;
use App\Service\MetaService;
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

    /**
     * @Route("/feed/search/")
     * @param Request $request
     * @return JsonResponse
     */
    public function searchAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository(FeedItem::class);

        $feedItems = $repository->createQueryBuilder('f')
            ->where('f.title LIKE :query')
            ->orWhere('f.description LIKE :query')
            ->setParameter('query', '%'.$request->get('query').'%')
            ->orderBy('f.pinned', 'DESC')
            ->orderBy('f.createdAt', 'DESC')
            ->getQuery()->getResult();

        return new JsonResponse([
            'status' => 'success',
            'data' => array_map(function(FeedItem $feedItem) {
                return [
                    'id' => $feedItem->getId(),
                    'title' => $feedItem->getTitle(),
                    'description' => $feedItem->getDescription(),
                    'url' => $feedItem->getUrl(),
                    'color' => $feedItem->getFeed()->getColor(),
                    'feedIcon' => $feedItem->getFeed()->getFeedIcon(),
                    'shareId' => $feedItem->getFeed()->getName() . '/' . $feedItem->getId() . '/',
                    'pinned' => $feedItem->getPinned()
                ];
            }, $feedItems)
        ]);
    }

    /**
     * @Route("/feeds/overview/")
     * @return Response
     */
    public function overviewAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository(Feed::class);

        return $this->render('widgets/feed-overview.html.twig', [
            'feeds' => $repository->findAll()
        ]);
    }

    /**
     * @Route("/chrome/import/")
     * @param Request $request
     * @param MetaService $metaService
     * @return JsonResponse
     */
    public function chromeImportAction(Request $request, MetaService $metaService)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $url = $request->get('url', '');

        if (strlen($url) === 0) {
            return new JsonResponse([
                'status' => 'fail',
                'message' => 'Missing parameter(s): url'
            ]);
        }

        $metaData = $metaService->getByUrl($url);
        $feed = $entityManager->getRepository(Feed::class)->findOneBy(['feedUrl'=>'']);

        $feedItem = new FeedItem();
        $feedItem
            ->setFeed($feed)
            ->setViewed(false)
            ->setGuid(intval(time()))
            ->setTitle($metaData->getTitle())
            ->setDescription($metaData->getMetaDescription())
            ->setPinned(true)
            ->setUrl($metaData->getUrl());

        $entityManager->persist($feedItem);
        $entityManager->flush();

        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'title' => $metaData->getTitle(),
                'description' => $metaData->getMetaDescription() ?: '',
                'url' => $metaData->getUrl(),
            ]
        ]);
    }

    /**
     * @Route("/meta/")
     * @param Request $request
     * @param MetaService $metaService
     * @return JsonResponse
     */
    public function MetaController(Request $request, MetaService $metaService)
    {
        $url = $request->get('url', '');
        if (strlen($url) === 0) {
            return new JsonResponse([
                'status' => 'fail',
                'message' => 'Missing parameter(s): url'
            ]);
        }

        $metaData = $metaService->getByUrl($url);

        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'title' => $metaData->getTitle(),
                'description' => (!empty($metaData->getMetaDescription()) ? $metaData->getMetaDescription() : '')
            ]
        ]);
    }
}