<?php

namespace Controller;

use Pimple\Container;

interface ContainerAwareInterface
{
    /**
     * @param $container Container
     */
    public function setContainer($container);
}
