<?php

namespace App\Controller;

use App\Entity\Feed;
use App\Entity\User;
use App\Entity\UserFeed;
use App\Repository\FeedItemRepository;
use App\Repository\UserFeedRepository;
use App\Service\FeedService;
use App\Service\ImportService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SettingsController extends AbstractController
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
        $this->userFeedRepository = $userFeedRepository;
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
        $feeds = $this->feedService->getFeeds();

        return $this->render('settings/index.html.twig', [
            'bodyClass' => 'settings',
            'userFeeds' => $userFeeds,
            'users' => $users,
            'feeds' => $feeds
        ]);
    }

    /**
     * @Route("/settings/feeds/add/")
     * @param Request $request
     * @return JsonResponse
     */
    public function addAction(Request $request)
    {
        $url = $this->validateUrl($request);
        if (strpos($url, 'Error') > 0) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $url
            ]);
        }

        $feed = $this->feedService->findOrCreateFeedByUrl($url);
        $feed->setUrl($url);
        $feed->setColor($request->get('color'));

        try {
            $feed->setName($this->importService->getFeedName($feed));
        } catch (\Exception $exception) {
            return new JsonResponse([
                'status' => 'error',
                'message' => '<b>Not a valid feed (' . $url . ')</b><br /><small>' . $exception . '</small>'
            ]);
        }

        $this->feedService->persistFeed($feed);

        if ($this->userFeedRepository->findOneBy(['user'=> $this->getUser(), 'feed'=> $feed])) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'This feed is already added to your account. Please refresh the browser if you\'re not seeing the feed. It might take some time before items show up, depending on feed updates.'
            ]);
        }

        $userFeed = $this->createUserFeed($feed, $request);

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

        if (strlen($request->get('color')) > 0) {
            $userFeed->setColor($request->get('color'));
        } elseif (strlen($request->get('icon')) > 0) {
            $icon = $request->get('icon');
            $userFeed->setIcon($icon === 'plus emptyIcon' ? '' : $icon);
        } else {
            $userFeed->setAutoPin(($request->get('autoPin') === "on"));
        }

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
     * @return RedirectResponse
     */
    public function disableXframeNotice()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $this->getUser()]);
        $user->setHideXframeNotice(true);
        $entityManager->persist($user);
        $entityManager->flush();

        return new RedirectResponse('/welcome/');
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function validateUrl(Request $request)
    {
        if (strlen($request->get('url'))) {
            $url = $request->get('url');
        } else {
            try {
                $url = $this->importService->findRSSFeed($request->get('website'));
            } catch (\Exception $exception) {
                return 'Error reading website';
            }
        }

        if ($url == '') {
            return 'Error: No RSS Feed found on website';
        }

        return $url;
    }

    /**
     * @param Feed $feed
     * @param Request $request
     * @return UserFeed|JsonResponse
     */
    protected function createUserFeed(Feed $feed, Request $request)
    {
        $userFeed = new UserFeed();
        $userFeed->setFeed($feed);
        $userFeed->setUser($this->getUser());
        $userFeed->setColor($request->get('color'));
        $userFeed->setIcon($request->get('icon'));
        $userFeed->setAutoPin(($request->get('autoPin') === "true"));

        $this->feedService->persistUserFeed($userFeed);

        $this->importService->addOldItems($feed, $userFeed);
        $this->importService->read($feed);
        return $userFeed;
    }
}
