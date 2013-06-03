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
		try { 
			// OPML-Datei einlesen
			$opml = \Core\XML\Element::loadFile($path);
		} catch(\Core\XML\Exception $exception) {
			throw new \Exception('Es wurde keine gültige OPML-Datei gesendet.', 1, $exception);
		}
		
		// Managervariable deklarieren
		$manager = $this;
		// Neue anonyme Funktion zum hinzufügen eines Feeds
		$addFeed = function($xmlURL, $currentGroup = NULL) use ($manager) {
			// Bereits vorhanden?
			if(!$manager->existObjectForRSS($xmlURL)) {
				// Request hinzufügen
				$request = new \Core\Header\Request($xmlURL);
				// Feed-Objekte erstellen
				$feed = new \Data\Feed($request);
				
				// Feed dem Manager hinzufügen
				$manager->addObject($feed);
			}
			
			// Feed der Gruppe hinfügen?
			if($currentGroup) $currentGroup->getRelationship()->addFeed($feed);
		};
		
		// Elemente durchlaufen
		foreach($opml->body->outline as $current) {
			if(!isset($current['type'])) { // Eine Gruppe?
				// Gruppe erstellen
				$group = new \Data\Group((string) $current['title']);
				// Gruppe dem Manager hinzufügen
				\Data\Group\Manager::main()->addObject($group);
				
				// Unterobjekte durchlaufen und hinzufügen
				foreach($current as $currentFeed) {
					// Gar kein RSS? 
					if((string)$currentFeed['type'] != 'rss') continue;					
					// Hinzufügen mit Gruppe
					$addFeed((string)$currentFeed['xmlUrl'], $group);
				}		
			} else if($current['type'] == 'rss') // Ein Feed
				$addFeed((string)$current['xmlUrl']);
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
			if($current->getRequest()->getURL() == $url) return true;
		}
		
		// Kein Feed vorhanden
		return false;
	}
}
?>