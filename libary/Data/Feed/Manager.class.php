<?php
/**
* Verwaltet die Feeds in der Datenbank.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/FeedSync/FeedSync
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
}
?>