<?php
/**
 * Created by PhpStorm.
 * User: petervandam
 * Date: 26/10/2016
 * Time: 15:06
 */

class UpdateFeeds
{
	public $feeds = array();

	public function __construct($feeds)
	{
		$this->feeds = $feeds;
	}

	public function updateFeeds()
	{
		foreach ($this->feeds as $feed) {
			$this->updateFeed($feed['url'], $feed['type']);
		}
	}

	private function updateFeed($url, $type)
	{

	}
}
