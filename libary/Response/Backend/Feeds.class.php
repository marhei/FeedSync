<?php
/**
* Feeds-Module für das Backend.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/marhei/FeedSync
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
		// Seitenoptionen hinzufügen
		\Response\Backend::setModuleVar('siteOptions', array(	'index.php?refreshFeeds=true'		=> 'Feeds neuladen',
																'index.php?markFeedsAsRead=true'	=> 'Feeds als gelesen markieren'));
		
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
			
			// Feeds neuladen
			if(\Core\Request::GET('refreshFeeds', false)) $this->refreshFeeds();
			// Feeds als gelesen markieren
			if(\Core\Request::GET('markFeedsAsRead', false)) $this->markFeedsAsRead();
		} catch(\Exception $exception) {
			\Response\Backend::setModuleVar('error', $exception->getMessage());
		}
	}
	
	/**
	* Fügt einen Feed hinzu.
	**/
	private function addFeed() {
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
	private function deleteFeed() {
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
	private function deleteFeedItems() {
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
	private function deleteReadFeedObjects() {
		// Feed-ID laden
		$feedID = \Core\Request::GET('feedID', -1);
		
		// Manager aufrufen
		$manager = \Data\Item\Manager::main();
		// Item löschen
		$manager->removeReadFeedObjects($feedID);
	}
	
	/**
	* Lädt alle Items neu.
	**/
	private function refreshFeeds() {
		// Alle RSS-Feeds abgleichen
		\Data\Feed\Manager::main()->updateAllItemLists();
	}
	
	/**
	* Markiert alle Feeds als gelesen.
	**/
	private function markFeedsAsRead() {
		throw new \Exception('Das geht leider noch nicht. :(', 2);
	}
}
?>