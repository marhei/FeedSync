<?php
/**
* Gibt Rückgabewerte für den Sync von FeedSync.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/FeedSync/FeedSync
* @date 2013-05-18
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Response\API;

class Sync {
	private $apiInstance, $manager;
	
	/**
	* Gibt der API-Datenklasse die API-Instanz mit und führt dann alles weiter aus.
	*
	* @param \Response\API $apiInstance
	**/
	public function __construct(\Response\API $apiInstance) {
		// Instanz in der Klasse speichern
		$this->apiInstance = $apiInstance;
		// Manager laden
		$this->manager = \Data\Item\Manager::main();
		
		// Die angeforderten Sync-Daten hinzufügen
		if(\Core\Request::issetGET('unread_item_ids')) $this->addUnreadIDs();
		if(\Core\Request::issetGET('saved_item_ids')) $this->addSavedIDs();
	}
	
	/**
	* Fügt alle IDs als Response hinzu, die ungelesen sind.
	**/
	public function addUnreadIDs() {
		// Array mit allen ungelesenen IDs
		$unreadIDs = array();	
		// Ungelesene laden
		$this->manager->loadUnread();
		
		// Alle Elemente durchluafen
		foreach($this->manager as $currentElement) {
			// Ungelesen? Array hinzufügen!
			if(!$currentElement->getAction()->isRead())
				$unreadIDs[] = $currentElement->getID();
		}
		
		// Als String hinzufügen
		$this->apiInstance->addResponse('unread_item_ids', implode(',', $unreadIDs));
	}
	
	/**
	* Fügt alle IDs als Response hinzu, die gespeichert sind.
	**/
	public function addSavedIDs() {
		// Array mit allen ungelesenen IDs
		$savedIDs = array();	
		// Ungelesene laden
		$this->manager->loadSaved();
		
		// Alle Elemente durchluafen
		foreach($this->manager as $currentElement) {
			// Gespeichert? Array hinzufügen!
			if($currentElement->getAction()->isSaved())
				$savedIDs[] = $currentElement->getID();
		}
		
		// Als String hinzufügen
		$this->apiInstance->addResponse('saved_item_ids', implode(',', $savedIDs));
	}
}
?>