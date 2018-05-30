<?php

namespace App\Controller;

use App\Entity\Feed;
use App\Entity\User;
use App\Entity\UserFeed;
use App\Repository\FeedItemRepository;
use App\Repository\UserFeedItemRepository;
use App\Repository\UserFeedRepository;
use App\Service\FeedService;
use App\Service\ImportService;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SettingsController extends Controller
{
    /**
     * @var FeedService
     */
    protected $feedService;

    /**
     * @var ImportService
     */
    protected $importService;

    /**
     * @var UserFeedRepository
     */
    protected $userFeedRepository;

    /**
     * @var FeedItemRepository
     */
    protected $feedItemRepository;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @param FeedService $feedService
     * @param ImportService $importService
     * @param UserFeedRepository $userFeedRepository
     * @param FeedItemRepository $feedItemRepository
     */
    public function __construct(FeedService $feedService, ImportService $importService, UserFeedRepository $userFeedRepository, FeedItemRepository $feedItemRepository, UserService $userService)
    {
        $this->feedService = $feedService;
        $this->importService = $importService;
        $this->userFeedRepository  = $userFeedRepository;
        $this->feedItemRepository = $feedItemRepository;
        $this->userService = $userService;
    }

    /**
     * @Route("/settings/")
     * @return Response
     */
    public function index()
    {
        $userFeeds = $this->feedService->getUserFeeds($this->getUser());
        $users = $this->userService->getAllUsers();

        return $this->render('settings/index.html.twig', [
            'bodyClass' => 'settings',
            'userFeeds' => $userFeeds,
            'users' => $users
        ]);
    }

    /**
     * @Route("/settings/feeds/add/")
     * @param Request $request
     * @return JsonResponse
     */
    public function addAction(Request $request)
    {
        if (
            strlen($request->get('url')) == 0
            && strlen($request->get('website')) > 0
        ) {
            $url = $this->importService->findRSSFeed($request->get('website'));
        } else {
            $url = $request->get('url');
        }

        $feed = $this->feedService->findOrCreateFeedByUrl($url);
        $feed->setUrl($request->get('url'));
        $feed->setName($this->importService->getFeedName($feed));
        $feed->setColor($request->get('color'));

        $this->feedService->persistFeed($feed);

        $userFeed = new UserFeed();
        $userFeed->setFeed($feed);
        $userFeed->setUser($this->getUser());
        $userFeed->setColor($request->get('color'));
        $userFeed->setIcon($request->get('icon'));
        $userFeed->setAutoPin(($request->get('autoPin') === "true"));

        $this->feedService->persistUserFeed($userFeed);
        $this->importService->read($feed);

        return new JsonResponse([
            'id' => $userFeed->getId(),
            'status' => 'success'
        ]);
    }

    /**
     * @Route("/settings/feeds/update/")
     * @param Request $request
     * @return JsonResponse
     */
    public function updateAction(Request $request)
    {
        $userFeed = $this->userFeedRepository->findOneBy(['id' => $request->get('id')]);
        $userFeed->setAutoPin(($request->get('autoPin') === "on"));
        $this->feedService->persistUserFeed($userFeed);

        return new JsonResponse([
            'id' => $userFeed->getId(),
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
        $userFeed = $this->userFeedRepository->findOneBy(['id' => $request->get('feedId')]);

        if (!$userFeed) {
            return new JsonResponse([
                'status' => 'fail',
                'message' => 'Feed not found'
            ]);
        }

        $this->feedService->removeUserFeed($userFeed);

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