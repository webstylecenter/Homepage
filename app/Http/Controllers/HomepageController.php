<?php
declare(strict_types=1);

namespace App\Controllers;

use Illuminate\Routing\Controller;
use Collective\Annotations\Routing\Annotations\Annotations\Get;
use Collective\Annotations\Routing\Annotations\Annotations\Middleware;
use Illuminate\View\View;

/**
 * @Controller(prefix="/")
 */
class HomepageController extends Controller
{
    /**
     * @Get("/", as="homepage")
     * @return View
     */
    public function index(): View
    {
        return view('index');
    }
}
