<?php
/**
* Start-Module für das Backend.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/FeedSync/FeedSync
* @date 2013-05-12
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Response\Backend;

class Start {
	/**
	* Konstruktor, der definiert, das ein Template aufgerufen werden soll.
	**/
	public function __construct() {
		// Template definieren
		\Response\Backend::setModuleVar('template', 'start');
		
		
		$feed = new \Data\Feed('http://www.tagesschau.de/xml/rss2');
		\Data\Feed\Manager::main()->addObject($feed);
	}
}
?>