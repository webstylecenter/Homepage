<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DroplistController extends AbstractController
{
    /**
     * @Route("/droplist/")
     * @return Response
     */
    public function index()
    {
        $items = json_decode(file_get_contents($_SERVER['DROP_LOCATION']), true);

        return $this->render('droplist/list.html.twig', [
            'imageList' => $items,
            'limit' => null,
            'bodyClass' => 'dropList'
        ]);
    }

    /**
     * @Route("/droplist/{limit}")
     * @param null|$limit
     * @return Response
     */
    public function limitedIndex($limit = null)
    {
        $items = json_decode(file_get_contents($_SERVER['DROP_LOCATION']), true);

        return $this->render('droplist/index.html.twig', [
            'imageList' => array_slice($items, 0, $limit),
            'limit' => $limit,
            'bodyClass' => 'dropList'
        ]);
    }
}
