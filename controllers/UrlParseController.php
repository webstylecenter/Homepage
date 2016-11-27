<?php
/**
 * Created by PhpStorm.
 * User: petervandam
 * Date: 26/11/2016
 * Time: 01:26
 */

$app->post('/parse-url/', function() use($app) {
    $urlParser = new \Service\UrlParseService();
    $parsedUrl = $urlParser->getMetaData($_POST['url']);

    return json_encode([
        'title' => $parsedUrl->getTitle(),
        'description' => (!empty($parsedUrl->getMetaDescription()) ? $parsedUrl->getMetaDescription() : '')
    ]);
});
