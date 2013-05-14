<?php
/**
* Verwaltet die Gruppen in der Datenbank.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/FeedSync/FeedSync
* @date 2013-05-10
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Data\Group;

class Manager extends \Core\Manager {
	const TABLE = 'groups';
	
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
	* Gibt alle Relationship-Objekte zurück.
	*
	* @return array
	**/
	public function getRelationshipObjects() {
		// Leeres Array
		$relationships = array();
		// Alle Gruppen durchlaufen
		foreach($this as $currentGroup)
			$relationships[] = $currentGroup->getRelationship();
			
		return $relationships;
	}
}