<?php

namespace Service;

use Entity\UrlParse;
use DOMDocument;

/**
 * Class UrlParseService
 * @package Service
 */
class UrlParseService
{
    /**
     * @param $url
     *
     * @return UrlParse
     */
    public function getMetaData($url)
    {
        $urlParser = new UrlParse();
        $urlParser->setUrl($url);

        $html = file_get_contents(strpos($url, 'http') === 0 ? $url : 'http://' . $url);

        $doc = new DOMDocument();
        @$doc->loadHTML($html);
        $nodes = $doc->getElementsByTagName('title');

        $urlParser->setTitle($nodes->item(0)->nodeValue);

        $metas = $doc->getElementsByTagName('meta');
        for ($i = 0; $i < $metas->length; $i++)
        {
            $meta = $metas->item($i);
            if($meta->getAttribute('name') == 'description')
                $urlParser->setMetaDescription($meta->getAttribute('content'));
        }

        return $urlParser;
    }
}
