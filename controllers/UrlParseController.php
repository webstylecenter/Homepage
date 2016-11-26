<?php
/**
 * Created by PhpStorm.
 * User: petervandam
 * Date: 26/11/2016
 * Time: 01:26
 */

$app->post('/parse-url/', function() use($app) {

    // TODO: Make this work the way it should
    $html = file_get_contents(strpos($_POST['url'], 'http') === 0 ? $_POST['url'] : 'http://' . $_POST['url']);

    //parsing begins here:
    $doc = new DOMDocument();
    @$doc->loadHTML($html);
    $nodes = $doc->getElementsByTagName('title');

    //get and display what you need:
    $title = $nodes->item(0)->nodeValue;

    $metas = $doc->getElementsByTagName('meta');

    for ($i = 0; $i < $metas->length; $i++)
    {
        $meta = $metas->item($i);
        if($meta->getAttribute('name') == 'description')
            $description = $meta->getAttribute('content');
        if($meta->getAttribute('name') == 'keywords')
            $keywords = $meta->getAttribute('content');
    }


    return json_encode([
        'title' => $title,
        'description' => (!empty($description) ? $description : '')
    ]);

});
