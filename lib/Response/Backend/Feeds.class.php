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
										array(	'javascript:refreshFeeds()'		=> \Core\Language::main()->get('feeds', 'refreshFeeds'),
												'javascript:markFeedsAsRead()'	=> \Core\Language::main()->get('feeds', 'markFeedsAsRead')));
		
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
			
			// Feed pausieren
			if(\Core\Request::GET('pauseFeed', false)) $this->pauseFeed();
			// Feed-Items sollen über Readability geschickt werden
			if(\Core\Request::GET('readabilityFeed', false)) $this->readabilityFeed();
			
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
		} catch(\Exception $exception) { echo $exception;
			throw new \Exception(\Core\Language::main()->get('feeds', 'errorInvalidOPML'), 3, $exception);
		}
	}
	
	/**
	* Fügt einen Feed hinzu.
	**/
	private function addFeed() {
		try {
			// HTTPS?
			$https = \Core\Request::POST('https', false);
			// Addon setzen
			$addon = $https ? 'https://' : 'http://';
		
			// Feed-URL laden
			$feedURL = $addon.\Core\Request::POST('feedURL');
			// Request hinzufügen
			$request = new \Core\Header\Request($feedURL);
			
			// Feed-Objekt erstellen
			$feedObject = new \Data\Feed($request);
			
			// Feed-Objekt dem Manager hinzufügen
			$this->manager->addObject($feedObject);
		} catch(\Exception $exception) {
			throw new \Exception(\Core\Language::main()->get('feeds', 'errorInvalidFeed'), 1, $exception);
		}
	}
	
	/**
	* Pausiert einen Feed.
	**/
	private function pauseFeed() {
		// Feed-ID laden
		$feedID = \Core\Request::GET('feedID', -1);
		// Feed laden
		$feedObject = $this->manager->getObjectForID($feedID);
	
		// Pausieren oder Unpausieren
		$pause = \Core\Request::GET('pause', false);
		// Entsprechende Methode aufrufen
		if($pause) $feedObject->pause();
		else $feedObject->unpause();
	}
	
	/**
	* Items werden in Zukunft über Readability geladen.
	**/
	private function readabilityFeed() {
		// Feed-ID laden
		$feedID = \Core\Request::GET('feedID', -1);
		// Feed laden
		$feedObject = $this->manager->getObjectForID($feedID);
	
		// Pausieren oder Unpausieren
		$readability = \Core\Request::GET('readability', false);
		// Entsprechende Methode aufrufen
		if($readability) {
			// Exception werfen wenn Readability nicht funktioniert
			if(!\Core\Readability::isValid()) throw new \Exception(\Core\Language::main()->get('feeds', 'errorNoReadability'), 2);
			
			// Markieren
			$feedObject->useReadability();
		} else $feedObject->unuseReadability();
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
		// Alle Feeds durchlaufen
		foreach($this->manager as $feed)
			$feed->markAsRead();
	}
}
?>