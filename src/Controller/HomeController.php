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
           'name' => 'Hello World!'
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
