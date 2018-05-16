<?php

$app->match('/meta/', function() {
    $meta = new \Service\MetaService();
    $metaData = $meta->getByUrl($_POST['url']);

    return json_encode([
        'status' => 'success',
        'data' => [
            'title' => $metaData->getTitle(),
            'description' => (!empty($metaData->getMetaDescription()) ? $metaData->getMetaDescription() : '')
        ]
    ]);
});
