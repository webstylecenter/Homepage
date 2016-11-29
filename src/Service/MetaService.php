<?php

namespace Service;

use Entity\Meta;
use DOMDocument;

/**
 * Class MetaService
 * @package Service
 */
class MetaService
{
    /**
     * @param $url
     *
     * @return Meta
     */
    public function getByUrl($url)
    {
        $url = strpos($url, 'http') === 0 ? $url : 'http://' . $url;
        $doc = $this->loadContent($url);

        $meta = new Meta();
        if (!$doc) {
            return $meta;
        }

        $meta->setUrl($url);
        $meta->setTitle($this->findTitle($doc));
        $meta->setMetaDescription($this->findMetaDescription($doc));

        return $meta;
    }

    /**
     * @param string $url
     * @return DOMDocument
     */
    protected function loadContent($url)
    {
        $options = [
            'http' => [
                'method' => 'GET',
                'header' => 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.98 Safari/537.36' . PHP_EOL
            ]
        ];

        $html = file_get_contents($url, false, stream_context_create($options));
        return @\DOMDocument::loadHTML($html);
    }

    /**
     * @param DOMDocument $doc
     *
     * @return string
     */
    protected function findTitle(DOMDocument $doc)
    {
        return $doc->getElementsByTagName('title')->item(0)->nodeValue;
    }

    /**
     * @param DOMDocument $doc
     *
     * @return string
     */
    protected function findMetaDescription(DOMDocument $doc)
    {
        $descriptionMap = [
            'description' => null,
            'og-description' => null
        ];

        $metas = $doc->getElementsByTagName('meta');
        for ($i = 0; $i < $metas->length; $i++) {
            $meta = $metas->item($i);
            $descriptionMap['description'] = $this->getMetaContent($descriptionMap, $meta, 'name', 'description');
            $descriptionMap['og-description'] = $this->getMetaContent($descriptionMap, $meta, 'property', 'og-description');
        }

        return $descriptionMap['og-description'] !== null
            ? $descriptionMap['og-description']
            : $descriptionMap['description'];
    }

    /**
     * @param array $currentMeta
     * @param array $meta
     * @param string $attribute
     * @param string $name
     *
     * @return string
     */
    protected function getMetaContent(array $currentMeta, array $meta, $attribute, $name)
    {

        if (!empty($currentMeta[$name])) {
            return $currentMeta[$name];
        }

        if ($meta->getAttribute($attribute) === $name) {
           $currentMeta[$name] = $meta->getAttribute('content');
        }

        return $currentMeta[$name];
    }
}
