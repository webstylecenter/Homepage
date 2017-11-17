<?php

foreach (glob( 'commands/*.php') as $file) {
    require_once $file;
    $command = ucfirst(str_replace('.php', '', $file));
    $command = '\\' . str_replace('/', '\\', $command);
    $app['console']->add(new $command($app));
}
