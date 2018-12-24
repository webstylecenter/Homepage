<?php

namespace App\Controller;

use App\Entity\FeedItem;
use App\Entity\FeedListFilter;
use App\Entity\Meta;
use App\Entity\UserFeedItem;
use App\Service\FeedService;
use App\Service\MetaService;
use FOS\UserBundle\Doctrine\UserManager;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class FeedController extends AbstractController
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
     * @var  UserManager
     */
    protected $userManager;

    /**
     * @var null|string
     */
    protected $postByEmail;

    /**
     * @param FeedService $feedService
     * @param MetaService $metaService
     * @param UserManagerInterface $userManager
     */
    public function __construct(FeedService $feedService, MetaService $metaService, UserManagerInterface $userManager)
    {
        $this->feedService = $feedService;
        $this->metaService = $metaService;
        $this->userManager = $userManager;
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
     * @Route("/chrome/import/{email}", defaults={"email"=0})
     * @param Request $request
     * @param null|string $email
     * @return JsonResponse
     */
    public function addFeedItemFromExtensionAction(Request $request, $email)
    {
        if (strlen($email) > 0) {
            $this->postByEmail = $email;
        }

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
            'title' => $metaData->getTitle(),
            'description' => $metaData->getMetaDescription(),
            'url' => $metaData->getUrl(),
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
            'data' => array_map(function(UserFeedItem $userFeedItem) {
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
     * @Route("/feed/set-opened/")
     * @param Request $request
     * @return JsonResponse
     */
    public function setOpenedItem(Request $request)
    {
        if ($request->get('userFeedItemId') === null) {
            return new JsonResponse([
                'status' => 'fail',
                'message' => 'No user feed item id has been set'
            ]);
        }

        $this->feedService->setOpenedItemsForUser($this->getUser(), (int) $request->get('userFeedItemId'));
        return new JsonResponse([
            'status' => 'success',
            'message' => 'Set item to opened'
        ]);
    }

    /**
     * @Route("/feed/opened/")
     * @return JsonResponse
     */
    public function getOpenedItems()
    {
        $userFeedItems = $this->feedService->getOpenedItemsForUser($this->getUser());
        return new JsonResponse([
            'status' => 'success',
            'items' => array_map(function(UserFeedItem $userFeedItem) {
                $feedItem = $userFeedItem->getFeedItem();
                return [
                    'id' => $userFeedItem->getId(),
                    'title' => $feedItem->getTitle(),
                    'description' => $feedItem->getDescription(),
                    'url' => $feedItem->getUrl(),
                    'color' => ($userFeedItem->getUserFeed() !== null ? $userFeedItem->getUserFeed()->getColor() : ''),
                    'feedIcon' => ($userFeedItem->getUserFeed() !== null ? $userFeedItem->getUserFeed()->getIcon() : ''),
                    'shareId' => ($userFeedItem->getUserFeed() !== null ? $userFeedItem->getUserFeed()->getFeed()->getName() : 'item') . '/' . $userFeedItem->getId() . '/',
                    'pinned' => $userFeedItem->isPinned()
                ];
            }, $userFeedItems)
        ]);
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

        $user = $this->getUser();
        if (!$user && strlen($this->postByEmail) > 0) {
            $user = $this->userManager->findUserByEmail($this->postByEmail);
        }

        $userFeedItem->setUser($user);
        $userFeedItem->setPinned(true);

        $this->feedService->persistUserFeedItem($userFeedItem);
    }
}
