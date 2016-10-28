<?php

foreach (glob('controllers/*.php') as $file) {
    require_once $file;
}
