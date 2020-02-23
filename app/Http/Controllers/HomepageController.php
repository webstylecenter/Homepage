<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\UserFeedItemRepository;
use Illuminate\Routing\Controller;
use Collective\Annotations\Routing\Annotations\Annotations\Get;
use Collective\Annotations\Routing\Annotations\Annotations\Middleware;
use Illuminate\Support\Facades\View;

/**
 * @Controller(prefix="/")
 */
class HomepageController extends Controller
{
    /**
     * @Get("/", as="homepage")
     * @Middleware("web")
     * @param UserFeedItemRepository $userFeedItemRepository
     * @return \Illuminate\Contracts\View\View
     */
    public function index(UserFeedItemRepository $userFeedItemRepository)
    {
        // Temporally
        $userFeedItems = $userFeedItemRepository->all();

        return View::make('home/index', [
            'bodyClass' => 'Homepage',
            'forecast' => [],
            'userFeedItems' => $userFeedItems,
            'settings' => [],
            'device' => [],
            'nextPageNumber' => 2,
        ]);
    }

    /**
     * @Get("/welcome/", as="welcome")
     * @Middleware("web")
     * @return \Illuminate\Contracts\View\View
     */
    public function container()
    {
        return View::make('welcome/index', [
            'bodyClass' => 'welcome',
            'isMobile' => false,
            'notes' => [],
            'todos' => []
        ]);
    }
}
