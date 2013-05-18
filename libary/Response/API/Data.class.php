<?php
/**
* Stellt die Daten für die API zur Verfügung.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/FeedSync/FeedSync
* @date 2013-05-18
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Response\API;

class Data {
	private $apiInstance;
	
	/**
	* Gibt der API-Datenklasse die API-Instanz mit und führt dann alles weiter aus.
	*
	* @param \Response\API $apiInstance
	**/
	public function __construct(\Response\API $apiInstance) {
		// Instanz in der Klasse speichern
		$this->apiInstance = $apiInstance;
		// Das letzte Aktuallisierungs-Datum aller Feeds anhängen
		$this->apiInstance->addResponse('last_refreshed_on_time', \Data\Feed\Manager::main()->getLastUpdate());
		
		// Die angeforderten Daten hinzufügen
		if(\Core\Request::issetGET('groups')) $this->addGroups();
		if(\Core\Request::issetGET('feeds')) $this->addFeeds();
		if(\Core\Request::issetGET('favicons')) $this->addFavicons();
		if(\Core\Request::issetGET('items')) $this->addItems();
		if(\Core\Request::issetGET('links')) $this->addLinks();
	}
	
	/**
	* Fügt die Gruppen-Daten hinzu.
	**/
	private function addGroups() {
		// Beziehungen mitschicken
		$this->addRelationships();
		// Daten vom Manager hinzufügen
		$this->addManagerData('groups', \Data\Group\Manager::main());
	}
	
	/**
	* Fügt die Feed-Daten hinzu.
	**/
	private function addFeeds() {
		// Beziehungen mitschicken
		$this->addRelationships();
		// Daten vom Manager hinzufügen
		$this->addManagerData('feeds', \Data\Feed\Manager::main());
	}
	
	/**
	* Fügt die Feed/Gruppen-Beziehungen hinzu.
	**/
	private function addRelationships() {
		// Gruppen-Manager laden
		$manager = \Data\Group\Manager::main();
	    // Alle Elemente laden
	    $manager->loadAll();
	
		// Daten hinzufügen
	    $this->apiInstance->addResponse('feeds_groups', $manager->getRelationshipObjects());
	}
	
	/**
	* Fügt die Favicon-Daten hinzu.
	**/
	private function addFavicons() {
		// Daten vom Manager hinzufügen
		$this->addManagerData('favicons', \Data\Favicon\Manager::main());
	}
	
	/**
	* Fügt die Item-Daten hinzu.
	**/
	private function addItems() {
		// Manager laden
		$manager = \Data\Item\Manager::main();
		
		/*if(is_numeric(\Core\Request::GET('since_id',false))) { // 50 neue Items seit dieser ID
				
		} else if(is_numeric(\Core\Request::GET('max_id',false))) { // 50 neue Items vor dieser ID
			
		} else if(\Core\Request::GET('with_ids',false)!==false) { // Items mit dieser ID
		
		} else { // Alle Items (Haha!)*/
			// Daten vom Manager hinzufügen
			$this->addManagerData('items', $manager);
		//}
		
		// Gesamt-Anzahl aller Items in der Datenbank
		$this->apiInstance->addResponse('total_items', $manager->countAll());
	}
	
	/**
	* Fügt die Hot Links hinzu. (Nicht unterstützt.)
	**/
	private function addLinks() {
		// Leeres Array hinzufügen
		$this->apiInstance->addResponse('links', array());
	}
	
	/**
	* Fügt Daten eines Managers dem Response-Array hinzu.
	*
	* @param string $key
	* @param \Core\Manager $manger
	**/
	private function addManagerData($key, \Core\Manager $manager) {
		// Daten laden
		$manager->loadAll();
		// Daten hinzufügen
		$this->apiInstance->addResponse($key, array_values($manager->getAllObjects()));
	}
}
?>