<?php
/**
* Feeds-Module für das Backend.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/FeedSync/FeedSync
* @date 2013-05-12
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Response\Backend;

class Feeds {
	private $manager;

	/**
	* Konstruktor, der definiert, das ein Template aufgerufen werden soll.
	**/
	public function __construct() {
		// Template definieren
		\Response\Backend::setModuleVar('template', 'feeds');
		
		// Manager laden
		$this->manager = \Data\Feed\Manager::main();
		// Alle Feeds laden
		$this->manager->loadAll();
		// Manager dem Modul hinzufügen
		\Response\Backend::setModuleVar('manager', $this->manager);
		
		try {
			// Feed löschen
			if(\Core\Request::GET('deleteFeed', false)) $this->deleteFeed();
			// Feed hinzufügen
			if(\Core\Request::GET('addFeed', false)) $this->addFeed();
			
			// Item löschen
			if(\Core\Request::GET('deleteFeedItems', false)) $this->deleteFeedItems();
			// Gelesene Items löschen
			if(\Core\Request::GET('deleteReadFeedItems', false)) $this->deleteReadFeedObjects();
		} catch(\Exception $exception) {
			\Response\Backend::setModuleVar('error', $exception->getMessage().' ('.$exception->getCode().')');
		}
	}
	
	/**
	* Fügt einen Feed hinzu.
	**/
	public function addFeed() {
		try {
			// Feed-URL laden
			$feedURL = 'http://'.\Core\Request::POST('feedURL');
			// Feed-Objekt erstellen
			$feedObject = new \Data\Feed($feedURL);
			
			// Feed-Objekt dem Manager hinzufügen
			$this->manager->addObject($feedObject);
		} catch(\Exception $exception) {
			throw new \Exception('Der Feed konnte nicht hinzugefügt werden. Eventuell ist das kein gültiger Feed.', 1, $exception);
		}
	}
	
	/**
	* Löscht einen Feed.
	**/
	public function deleteFeed() {
		// Feed-ID laden
		$feedID = \Core\Request::GET('feedID', -1);
		// Feed laden
		$feedObject = $this->manager->getObjectForID($feedID);
		
		// Löschen
		$this->manager->removeObject($feedID);
		// Einträge löschen
		$this->deleteFeedItems();
		
		// Favicon-ID laden
		$faviconID = $feedObject->getFavicon()->getID();
		// Favicon löschen
		\Data\Favicon\Manager::main()->removeObject($faviconID);
	}
	
	/**
	* Löscht den Inhalt eines Feeds.
	**/
	public function deleteFeedItems() {
		// Feed-ID laden
		$feedID = \Core\Request::GET('feedID', -1);
		
		// Manager aufrufen
		$manager = \Data\Item\Manager::main();
		// Item löschen
		$manager->removeFeedObjects($feedID);
	}
	
	/**
	* Löscht den gelesenen Inhalt eines Feeds.
	**/
	public function deleteReadFeedObjects() {
		// Feed-ID laden
		$feedID = \Core\Request::GET('feedID', -1);
		
		// Manager aufrufen
		$manager = \Data\Item\Manager::main();
		// Item löschen
		$manager->removeReadFeedObjects($feedID);
	}
}
?>