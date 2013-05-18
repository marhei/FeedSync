<?php
/**
* Verwaltet die Items in der Datenbank.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/FeedSync/FeedSync
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
		$whereString = '';
		$more = '';
		
		// Alle IDs durchlaufen
		foreach($ids as $current) {
			$whereString .= $more.'id = '.\Core\MySQL::main()->quoteString($current);
			$more = ' OR ';
		}
	
		// Alle Objekte laden
		$queryObject = $this->tableActions->select($whereString);
		// Zu dem Manager hinzufügen
		$this->saveInInstance($queryObject);
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
						'saved'			=> $object->getAction()->isSaved(),
						'read'			=> $object->getAction()->isRead());
	}
}
?>