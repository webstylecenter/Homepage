<?php
declare(strict_types=1);

use App\Http\Controllers\Controller;

/**
 * Class HomepageController
 * @\Collective\Annotations\Routing\Annotations\Annotations\Controller(prefix="/")
 */
class HomepageController extends Controller
{
    protected FeedService $feedService;
}
