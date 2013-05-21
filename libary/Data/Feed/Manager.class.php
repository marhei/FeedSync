<?php
/**
* Verwaltet die Feeds in der Datenbank.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/marhei/FeedSync
* @date 2013-05-10
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Data\Feed;

class Manager extends \Core\Manager {
	const TABLE = 'feeds';
	
	protected static $mainInstance;
	
	/**
	* Gibt das Content-Array für ein Objekt zurück
	*
	* @param object $object - Das Objekt
	* @return array - Content-Array
	**/
	protected function getContentArrayForObject($object) {
		return array('object' => serialize($object));
	}
	
	/**
	* Gibt zurück das Datum des aktuellsten Beitrags zurück.
	*
	* @return int
	**/
	public function getLastUpdate() {
		// Alle Inhalte laden
		$this->loadAll();
		
		// Variable vor definieren
		$lastUpdate = 0;
		// Alle Elemente durchlaufen
		foreach($this as $current) {
			// Noch neuer?
			if($current->getLastUpdate() > $lastUpdate)
				$lastUpdate = $current->getLastUpdate();
		}
		
		// Ergebnis zurückgeben
		return $lastUpdate;
	}
	
	/**
	* Updatet alle Item-Listen.
	**/
	public function updateAllItemLists() {
		// Alle Inhalte laden
		$this->loadAll();
		
		// Alle Elemente durchlaufen
		foreach($this as $current)
			$current->updateItemList();
	}
	
	/**
	* Fügt Feeds aus einer OPML-Datei hinzu.
	*
	* @param string $path - Pfad zur OPML-Datei
	**/
	public function addObjectsFromOPML($path) {
		// OPML-Datei einlesen
		$opml = \Core\XML\Element::loadFile($path);
		
		// Elemente durchlaufen
		foreach($opml->body->outline as $current) {
			// XML-URL laden
			$xmlURL = (string) $current['xmlUrl'];
			// Bereits vorhanden?
			if($this->existObjectForRSS($xmlURL)) continue;
			
			// Feed-Objekte erstellen
			$feed = new \Data\Feed($xmlURL);
			// Feed dem Manager hinzufügen
			$this->addObject($feed);
		}
	}
	
	/**
	* Überprüft, ob eine Feed mit der angegeben URL exisitert.
	*
	* @param string $url
	* @return bool
	**/
	public function existObjectForRSS($url) {
		// Alle Feeds laden
		$this->loadAll();
		
		// Feed durchlaufen
		foreach($this as $current) {
			if($current->getURL() == $url) return true;
		}
		
		// Kein Feed vorhanden
		return false;
	}
}
?>