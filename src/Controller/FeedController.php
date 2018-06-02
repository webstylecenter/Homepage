<?php

namespace App\Controller;

use App\Entity\FeedItem;
use App\Entity\FeedListFilter;
use App\Entity\Meta;
use App\Entity\UserFeedItem;
use App\Service\FeedService;
use App\Service\MetaService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FeedController extends Controller
{
    /**
     * @var FeedService
     */
    protected $feedService;

    /**
     * @var MetaService
     */
    protected $metaService;

    /**
     * @param FeedService $feedService
     * @param MetaService $metaService
     */
    public function __construct(FeedService $feedService, MetaService $metaService)
    {
        $this->feedService = $feedService;
        $this->metaService = $metaService;
    }

    /**
     * @Route("/feed/add-item/")
     * @param Request $request
     * @return JsonResponse
     */
    public function addFeedItemAction(Request $request)
    {
        $metaData = new Meta;
        $metaData->setTitle($request->get('title'));
        $metaData->setMetaDescription($request->get('description'));
        $metaData->setUrl($request->get('url'));

        if (!$request->get('title')) {
            $metaData = $this->metaService->getByUrl($request->get('url'));
        }

        $this->createFeedItem($metaData);

        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'title' => $metaData->getTitle(),
                'description' => $metaData->getMetaDescription(),
                'url' => $metaData->getUrl(),
            ]
        ]);
    }

    /**
     * @Route("/chrome/import/")
     * @param Request $request
     * @return JsonResponse
     */
    public function addFeedItemFromExtensionAction(Request $request)
    {
        $metaData = $this->metaService->getByUrl($request->get('url'));

        if (!$metaData) {
            return new JsonResponse([
                'status' => 'fail',
                'message' => 'No metadata found for url "' . $request->get('url') . '"'
            ]);
        }

        try {
           $this->createFeedItem($metaData);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $exception
            ]);
        }

        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'title' => $metaData->getTitle(),
                'description' => $metaData->getMetaDescription(),
                'url' => $metaData->getUrl(),
            ]
        ]);
    }

    /**
     * @Route("/meta/")
     * @param Request $request
     * @return JsonResponse
     */
    public function MetaController(Request $request)
    {
        $metaData = $this->metaService->getByUrl($request->get('url'));
        if (!$metaData) {
            return new JsonResponse([
                'status' => 'fail',
                'message' => 'No metadata found for url "' . $request->get('url') . '"'
            ]);
        }
        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'title' => $metaData->getTitle(),
                'description' => $metaData->getMetaDescription()
            ]
        ]);
    }

    /**
     * @Route("/feed/pin/{userFeedItem}")
     * @param UserFeedItem $userFeedItem
     * @return JsonResponse
     */
    public function pinAction(UserFeedItem $userFeedItem)
    {
        $userFeedItem->setPinned(!$userFeedItem->isPinned());
        $this->feedService->persistUserFeedItem($userFeedItem);

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Pin toggled'
        ]);
    }

    /**
     * @Route("/feed/refresh/")
     * @return Response
     */
    public function refreshAction()
    {
        return $this->render('home/components/newsfeed.html.twig', [
            'userFeedItems' => $this->feedService->getUserFeedItemsWithFilter((new FeedListFilter())
                ->setUser($this->getUser())
                ->setNewOnly(true)
            ),
        ]);
    }

    /**
     * @Route("/feed/page/{page}")
     * @param integer $page
     * @return Response
     */
    public function loadExtraAction($page)
    {
        return $this->render('home/components/newsfeed.html.twig', [
            'userFeedItems' => $this->feedService->getUserFeedItemsWithFilter((new FeedListFilter())
                ->setUser($this->getUser())
                ->setPage((int) $page)
            ),
            'nextPageNumber' => $page + 1,
        ]);
    }

    /**
     * @Route("/feed/search/")
     * @param Request $request
     * @return JsonResponse
     */
    public function searchAction(Request $request)
    {
        $userFeedItems = $this->feedService->getUserFeedItemsWithFilter((new FeedListFilter())
            ->setUser($this->getUser())
            ->setSearchQuery($request->get('query'))
        );

        return new JsonResponse([
            'status' => 'success',
            'data' => array_map(function (UserFeedItem $userFeedItem) {
                $feedItem = $userFeedItem->getFeedItem();
                return [
                    'id' => $userFeedItem->getId(),
                    'title' => $feedItem->getTitle(),
                    'description' => $feedItem->getDescription(),
                    'url' => $feedItem->getUrl(),
                    'color' => ($userFeedItem->getUserFeed() !== null ? $userFeedItem->getUserFeed()->getColor() : ''),
                    'feedIcon' => ($userFeedItem->getUserFeed() !== null ? $userFeedItem->getUserFeed()->getIcon() : ''),
                    'shareId' => ($userFeedItem->getUserFeed() !== null ? $userFeedItem->getUserFeed()->getFeed()->getName() : 'item') . '/' . $userFeedItem->getId() . '/',
                    'pinned' => $userFeedItem->isPinned(),
                    'user' => $this->getUser()
                ];
            }, $userFeedItems)
        ]);
    }

    /**
     * @Route("/feeds/overview/")
     * @return Response
     */
    public function overviewAction()
    {
        return $this->render('widgets/feed-overview.html.twig', [
            'userFeeds' => $this->feedService->getUserFeeds($this->getUser())
        ]);
    }

    /**
     * @Route("/feed/check-header/", name="checkHeader")
     * @param Request $request
     * @return JsonResponse
     */
    public function checkForXFrameHeader(Request $request)
    {
        $header = @get_headers($request->get('url'), 1);
        return new JsonResponse([
            'found' => isset($header['X-Frame-Options']),
        ]);
    }

    /**
     * @Route("/feed/opened-in-popup/")
     * @return Response
     */
    public function popupAction()
    {
        return $this->render('widgets/opened-in-popup.html.twig');
    }

    /**
     * @Route("/share/{feedName}/{id}/")
     * @param $feedname
     * @param $id
     * @return RedirectResponse
     */
    public function openSharedFeedItem($feedname = null, $id)
    {
        $userFeedItem = $this->feedService->findUserFeedItemUrl($id);
        return new RedirectResponse($userFeedItem->getFeedItem()->getUrl());
    }

    /**
     * @param Meta $meta
     */
    private function createFeedItem(Meta $meta)
    {
        $feedItem = new FeedItem();
        $feedItem->setGuid(intval(time()));
        $feedItem->setTitle($meta->getTitle());
        $feedItem->setDescription((strlen($meta->getMetaDescription()) > 0 ? $meta->getMetaDescription() : ''));
        $feedItem->setUrl($meta->getUrl());

        $userFeedItem = new UserFeedItem();
        $userFeedItem->setFeedItem($feedItem);
        $userFeedItem->setUser($this->getUser());
        $userFeedItem->setPinned(true);

        $this->feedService->persistUserFeedItem($userFeedItem);
    }
}