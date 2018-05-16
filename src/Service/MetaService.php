<?php

namespace Service;

use Entity\Meta;

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
        $meta = new Meta;

        $url = strpos($url, 'http') === 0 ? $url : 'http://' . $url;
        $doc = $this->loadContent($url);
        $meta->setUrl($url);
        $meta->setTitle($this->findTitle($doc) ?: $url);
        $meta->setMetaDescription($this->findMetaDescription($doc));

        return $meta;
    }

    /**
     * @param $url
     * @return boolean|string
     */
    protected function loadContent($url)
    {
        $options = [
            'http' => [
                'method' => 'GET',
                'header' => 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.98 Safari/537.36' . PHP_EOL
            ]
        ];

        $html = @file_get_contents($url, false, stream_context_create($options));
        return @\DOMDocument::loadHTML((string) $html);
    }

    /**
     * @param \DOMDocument $doc
     *
     * @return string|null
     */
    protected function findTitle(\DOMDocument $doc)
    {
        $titleNode = $doc->getElementsByTagName('title');
        return $titleNode->item(0) ? $titleNode->item(0)->nodeValue : null;
    }

    /**
     * @param \DOMDocument $doc
     *
     * @return string
     */
    protected function findMetaDescription(\DOMDocument $doc)
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

        return $descriptionMap['og-description'] ?: $descriptionMap['description'];
    }

    /**
     * @param array $currentMeta
     * @param \DOMNode $meta
     * @param string $attribute
     * @param string $name
     *
     * @return string
     */
    protected function getMetaContent(array $currentMeta, \DOMNode $meta, $attribute, $name)
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
