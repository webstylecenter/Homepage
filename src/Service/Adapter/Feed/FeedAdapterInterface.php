<?php

namespace Service\Adapter\Feed;

use Entity\FeedItem;
use Zend\Feed\Reader\ReaderImportInterface;

interface FeedAdapterInterface
{
    /**
     * @param ReaderImportInterface $reader
     */
    public function __construct(ReaderImportInterface $reader);

	/**
	 * @return FeedItem[]
	 */
	public function read();

    /**
     * @return string
     */
    public function getName();
}
