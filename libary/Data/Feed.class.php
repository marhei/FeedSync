<?php
/**
* Eine RSS-Feed, der ein Favicon, ein Title, eine URL, eine Seiten-URL und das letzte Update beinhaltet.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/FeedSync/FeedSync
* @date 2013-05-08
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Data;

class Feed implements \JsonSerializable, \Core\Manager\Indentable {
	private $id, $faviconID, $title, $url, $siteURL, $lastUpdate;
	
	/**
	* Gibt die Rückgabewerte für die API zurück.
	*
	* @return array
	**/
	public function jsonSerialize() {
		return array(	'id'					=> $this->id,
						'favicon_id'			=> $this->faviconID,
						'title'					=> $this->title,
						'url'					=> $this->url,
						'site_url'				=> $this->siteURL,
						'is_spark'				=> false,
						'last_updated_on_time'	=> $this->lastUpdate);
	}
	
	/**
	* Erstellt einen neuen Feed.
	*
	* @param string $url - URL des Feeds
	**/
	public function __construct($url) {
		// Die URL des Feeds speichern
		$this->url = $url;
		// Feeddaten laden
		$this->updateFeedInformation();
		
		// Favicon laden
		$favicon = new Favicon($this->siteURL);
		// Favicon dem Manager hinzufügen
		Favicon\Manager::main()->addObject($favicon);
		// Favicon-ID dem Feed hinzufügen
		$this->faviconID = $favicon->getID();
	}
	
	/**
	* Gibt den Feed als Objekt zurück.
	*
	* @return XMLElement
	**/
	private function getFeedObject() {
		// Den Feed öffnen
		return \Core\XMLElement::loadFile($this->url)->channel[0];
	}
	
	/**
	* Updatet die Feed-Daten aus der URL.
	**/
	private function updateFeedInformation() {
		// Den Feed öffnen
		$feed = $this->getFeedObject();
		
		// Daten auslesen
		$this->title = (string) $feed->title;
		$this->siteURL = (string) $feed->link;
		$this->lastUpdate = strtotime((string) $feed->lastBuildDate);
	}
	
	/**
	* Gibt die ID des Feeds zurück.
	*
	* @return int
	**/
	public function getID() {
		return $this->id;
	}
	
	/**
	* Setzt die ID des Feeds.
	*
	* @param int $id
	**/
	public function setID($id) {
		$this->id = $id;
	}
	
	/**
	* Gibt das Favicon zurück.
	*
	* @return Favicon
	**/
	public function getFavicon() {
		// Manager laden
		$faviconManager = Favicon\Manager::main();
		// Alle Favicons laden
		$faviconManager->loadAll();
	
		// Vom Manager laden
		return $faviconManager->getObjectForID($this->faviconID);
	}
	
	/**
	* Gibt den Titel des Feeds zurück.
	*
	* @return string
	**/
	public function getTitle() {
		return $this->title;
	}
	
	/**
	* Gibt die URL des Feeds zurück.
	*
	* @return string
	**/
	public function getURL() {
		return $this->url;
	}
	
	/**
	* Gibt die Seiten-URL des Feeds zurück.
	*
	* @return string
	**/
	public function getSiteURL() {
		return $this->siteURL;
	}
	
	/**
	* Gibt das letze Update zurück.
	*
	* @return int
	**/
	public function getLastUpdate() {
		return $this->lastUpdate;
	}
}
?>