<?php
/**
 * Created by PhpStorm.
 * User: petervandam
 * Date: 26/11/2016
 * Time: 01:26
 */

$app->post('/meta/', function() use($app) {
    $meta = new \Service\MetaService();
    $metaData = $meta->getByUrl($_POST['url']);

    return json_encode([
        'title' => $metaData->getTitle(),
        'description' => (!empty($metaData->getMetaDescription()) ? $metaData->getMetaDescription() : '')
    ]);
});
