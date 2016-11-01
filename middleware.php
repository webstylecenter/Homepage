<?php

foreach (glob('middlewares/*.php') as $file) {
    require_once $file;
}
