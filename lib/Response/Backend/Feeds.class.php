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
		\Response\Backend::setModuleVar('siteOptions',
										array(	\Response\Backend::cml(array('refresh'=>''))			=> \Core\Language::main()->get('feeds', 'refreshFeeds'),
												\Response\Backend::cml(array('markFeedsAsRead'=>true))	=> \Core\Language::main()->get('feeds', 'markFeedsAsRead')));
		
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
			// OPML-Dateien importieren
			if(\Core\Request::GET('importOPML', false)) $this->importOPML();
			
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
	* Importiert eine OPML-Datei.
	**/
	private function importOPML() {
		// Datei fetchen
		$opmlFile = \Core\Request::File('opmlFile');
		// Keine Datei hochgeladen?
		if(is_null($opmlFile) || $opmlFile['error'] == UPLOAD_ERR_NO_FILE)
			throw new \Exception(\Core\Language::main()->get('feeds', 'errorNoOPML'), 2);
		
		try {
			// Dem Manager hinzufügen
			$this->manager->addObjectsFromOPML($opmlFile['tmp_name']);
		} catch(\Exception $exception) {
			throw new \Exception(\Core\Language::main()->get('feeds', 'errorInvalidOPML'), 3, $exception);
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
			throw new \Exception(\Core\Language::main()->get('feeds', 'errorInvalidFeed'), 1, $exception);
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
	* Markiert alle Feeds als gelesen.
	**/
	private function markFeedsAsRead() {
		// Feed-Manager
		$manager = \Data\Feed\Manager::main();
		// Alle Feeds laden
		$manager->loadAll();
		
		// Alle Feeds durchlaufen
		foreach($manager as $feed)
			$feed->markAsRead();
	}
}
?>