<?php
/**
* Eine Beziehung zwischen einer Gruppe und einer beliebigen Anzahl von Feeds.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/FeedSync/FeedSync
* @date 2013-05-14
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Data\Group;

class Relationship implements \JsonSerializable {
	private $id, $feeds = array();
	
	/**
	* Gibt die Rückgabewerte für die API zurück.
	*
	* @return array
	**/
	public function jsonSerialize() {
		return array(	'group_id'	=> $this->id,
						'feed_ids'	=> implode(',', $this->feeds));
	}
	
	/**
	* Gibt die ID der Gruppe zurück.
	*
	* @return int
	**/
	public function getID() {
		return $this->id;
	}
	
	/**
	* Setzt die ID der Gruppe.
	*
	* @param int $id
	**/
	public function setID($id) {
		$this->id = $id;
	}

	/**
	* Fügt eine Feed der Beziehung hinzu.
	*
	* @param \Data\Feed $feed
	**/
	public function addFeed(\Data\Feed $feed) {
		// ID dem Array hinzufügen
		$this->feeds[] = $feed->getID();
	}
	
	/**
	* Löscht einen Feed aus dem Array.
	*
	* @param int $id
	**/
	public function removeID($id) {
		// Das Element überhaupt vorhanden?
		if(!in_array($id, $this->feeds)) return;
		
		// Nach der ID suchen
		$arrayIndex = array_search($id, $this->feeds);
		// Aus dem Array löschen
		unset($this->feeds[$arrayIndex]);
	}
}
?>