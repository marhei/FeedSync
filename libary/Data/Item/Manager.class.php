<?php
/**
* Verwaltet die Items in der Datenbank.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/marhei/FeedSync
* @date 2013-05-14
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Data\Item;

class Manager extends \Core\Manager {
	const TABLE = 'items';
	
	protected static $mainInstance;
	
	/**
	* Lädt 50 Objekte seit einer ID aus der DB.
	*
	* @param int $id
	**/
	public function loadSinceID($id) {
		// ID maskieren
		$id = \Core\MySQL::main()->quoteString($id);
	
		// Alle Objekte laden
		$queryObject = $this->tableActions->select(" id > ".$id." ORDER BY id DESC LIMIT 0,50");
		// Zu dem Manager hinzufügen
		$this->saveInInstance($queryObject);
	}
	
	/**
	* Lädt 50 Objekte bevor einer ID aus der DB.
	*
	* @param int $id
	**/
	public function loadBeforeID($id) {
		// ID maskieren
		$id = \Core\MySQL::main()->quoteString($id);
	
		// Alle Objekte laden
		$queryObject = $this->tableActions->select(" id < ".$id." ORDER BY id ASC LIMIT 0,50");
		// Zu dem Manager hinzufügen
		$this->saveInInstance($queryObject);
	}
	
	/**
	* Lädt beliebige Objekte
	*
	* @param array $id
	**/
	public function loadIDs(array $ids) {
		// Nur IDs laden, die noch nicht geladen wurden.
		$loadIDs = array();
		foreach($ids as $current) {
			if(!$this->existObjectForID($current)) $loadIDs[] = $current;
		}
	
		$whereString = '';
		$more = '';
		
		// Alle IDs durchlaufen
		foreach($loadIDs as $current) {
			$whereString .= $more.'id = '.\Core\MySQL::main()->quoteString($current);
			$more = ' OR ';
		}
	
		// Alle Objekte laden
		$queryObject = $this->tableActions->select($whereString);
		// Zu dem Manager hinzufügen
		$this->saveInInstance($queryObject);
	}
	
	/**
	* Lädt alle ungelesene Items.
	**/
	public function loadUnread() {
		// Alle Objekte laden
		$queryObject = $this->tableActions->select(array('read'=>0));
		// Zu dem Manager hinzufügen
		$this->saveInInstance($queryObject);
	}
	
	/**
	* Lädt alle gespeicherten Items.
	**/
	public function loadSaved() {
		// Alle Objekte laden
		$queryObject = $this->tableActions->select(array('saved'=>1));
		// Zu dem Manager hinzufügen
		$this->saveInInstance($queryObject);
	}
	
	/**
	* Lädt alle Items für einen Feed.
	*
	* @param int $feedID - ID des Feeds
	**/
	public function loadUnreadInFeed($feedID) {
		// Objekte laden
		$queryObject =  $this->tableActions->select(array('feedID'=>$feedID, 'read'=>0));
		// Zu dem Manager hinzufügen
		$this->saveInInstance($queryObject);
	}
	
	/**
	* Zählt alle Items für einen Feed.
	*
	* @param int $feedID - ID des Feeds
	* @return int
	**/
	public function countAllInFeed($feedID) {
		return $this->tableActions->count(array('feedID'=>$feedID));
	}
	
	/**
	* Zählt alle Items für einen Feed.
	*
	* @param int $feedID - ID des Feeds
	* @return int
	**/
	public function countUnreadInFeed($feedID) {
		return $this->tableActions->count(array('feedID'=>$feedID, 'read'=>0));
	}
	
	/**
	* Löscht alle Items eines Feeds.
	*
	* @param int $feedID - ID des Feeds
	**/
	public function removeFeedObjects($feedID) {
		// Aus der DB löschen
		$this->tableActions->delete(array('feedID'=>$feedID));
		
		// Alle Elemente durchlaufen
		foreach($this as $id => $current) {
			// Nicht der richtige Feed
			if($current->getFeedID() != $feedID) continue;
			
			// Einträge in der Klasse löschen
			unset($this->objects[$id]);
			unset($this->unchangedObjects[$id]);
		}
	}
	
	/**
	* Löscht alle ungelesenen Items eines Feeds.
	*
	* @param int $feedID - ID des Feeds
	**/
	public function removeReadFeedObjects($feedID) {
		// Aus der DB löschen
		$this->tableActions->delete(array('feedID'=>$feedID, 'read'=>true));
		
		// Alle Elemente durchlaufen
		foreach($this as $id => $current) {
			// Nicht der richtige Feed
			if($current->getFeedID() != $feedID || !$current->isRead()) continue;
			
			// Einträge in der Klasse löschen
			unset($this->objects[$id]);
			unset($this->unchangedObjects[$id]);
		}
	}
	
	/**
	* Gibt das Content-Array für ein Objekt zurück
	*
	* @param object $object - Das Objekt
	* @return array - Content-Array
	**/
	protected function getContentArrayForObject($object) {
		return array(	'object' 		=> serialize($object),
						'createTime'	=> $object->getCreateTime(),
						'feedID'		=> $object->getFeedID(),
						'saved'			=> $object->getAction()->isSaved(),
						'read'			=> $object->getAction()->isRead());
	}
}
?>