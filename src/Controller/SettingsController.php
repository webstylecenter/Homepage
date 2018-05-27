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
     * @param FeedService $feedService
     * @param ImportService $importService
     * @param UserFeedRepository $userFeedRepository
     * @param FeedItemRepository $feedItemRepository
     */
    public function __construct(FeedService $feedService, ImportService $importService, UserFeedRepository $userFeedRepository, FeedItemRepository $feedItemRepository)
    {
        $this->feedService = $feedService;
        $this->importService = $importService;
        $this->userFeedRepository  = $userFeedRepository;
        $this->feedItemRepository = $feedItemRepository;
    }

    /**
     * @Route("/settings/")
     * @return Response
     */
    public function index()
    {
        $userFeeds = $this->feedService->getUserFeeds($this->getUser());

        return $this->render('settings/index.html.twig', [
            'bodyClass' => 'settings',
            'userFeeds' => $userFeeds
        ]);
    }

    /**
     * @Route("/settings/feeds/add/")
     * @param Request $request
     * @return JsonResponse
     */
    public function addAction(Request $request)
    {
        $feed = $this->feedService->findOrCreateFeedByUrl($request->get('url'));
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