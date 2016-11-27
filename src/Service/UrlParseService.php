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
        $url = strpos($url, 'http') === 0 ? $url : 'http://' . $url;
        $urlParser = new UrlParse();
        $urlParser->setUrl($url);

        $doc = $this->loadContent($url);
        $urlParser->setTitle($this->findTitle($doc));
        $urlParser->setMetaDescription($this->findMetaDescription($doc));

        return $urlParser;
    }

    /**
     * @param $url
     *
     * @return DOMDocument
     */
    public function loadContent($url)
    {
        $html = file_get_contents($url);
        $doc = new DOMDocument();
        @$doc->loadHTML($html);
        return $doc;
    }

    /**
     * @param DOMDocument $doc
     *
     * @return string
     */
    public function findTitle(DOMDocument $doc)
    {
        $nodes = $doc->getElementsByTagName('title');
        return $nodes->item(0)->nodeValue;;
    }

    /**
     * @param DOMDocument $doc
     *
     * @return string
     */
    public function findMetaDescription(DOMDocument $doc)
    {
        $metas = $doc->getElementsByTagName('meta');
        for ($i = 0; $i < $metas->length; $i++)
        {
            $meta = $metas->item($i);
            if($meta->getAttribute('name') == 'description')
               return $meta->getAttribute('content');
        }
    }
}
