<?php

foreach (glob('controllers/*.php') as $file) {
    require_once __DIR__ . '/' . $file;
}
