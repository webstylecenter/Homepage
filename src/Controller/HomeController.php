<?php

namespace Controller;

use Pimple\Container;

class HomeController implements ContainerAwareInterface
{
    /**
     * @var Container
     */
    protected $container;

    public function homeAction()
    {
        return $this->getTwig()->render('home/index.html.twig', [
            'title' => 'Homepage',
            'subtitle'=> 'Peter van Dam\'s homepage',
            'feeditems'=> [
                [
                    'title' => 'Test',
                    'description' => 'Omschrijving',
                    'url' => 'http://neowin.net'
                ],[
                    'title' => 'Test 2',
                    'description' => 'Omschrijving 2',
                    'url' => 'http://neowin.net'
                ],
            ]
        ]);
    }

    /**
     * @return Container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * @return \Twig_Environment
     */
    protected function getTwig()
    {
        return $this->container['twig'];
    }
}
