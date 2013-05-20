<?php
/**
* Zum schreiben von Informationen über Items.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/marhei/FeedSync
* @date 2013-05-18
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Response\API;

class Write {
	private $apiInstance;
	
	/**
	* Gibt der API-Datenklasse die API-Instanz mit und führt dann alles weiter aus.
	*
	* @param \Response\API $apiInstance
	**/
	public function __construct(\Response\API $apiInstance) {
		// Instanz in der Klasse speichern
		$this->apiInstance = $apiInstance;
		
		// Daten auslesen
		$type = \Core\Request::POST('mark');
		$id = \Core\Request::POST('id');
		$as = \Core\Request::POST('as');
		$before = \Core\Request::POST('before');
		
		// Was soll markiert werden?
		switch($type) {
			case 'item':
				\Data\Item\Manager::main()->markItemAs($id, $as);
				break;
				
			case 'feed':
				$this->markFeedAsRead($id, $before);
				break;
				
			case 'group':
				$this->markGroupAsRead($id, $before);
				break;
		}
	}
	
	/**
	* Markiert einen Feed als gelesen.
	*
	* @param int $feedID
	* @param int $before
	**/
	private function markFeedAsRead($feedID, $before) {
		// Feed-Manager
		$manager = \Data\Feed\Manager::main();
		// Alle Feeds laden
		$manager->loadAll();
		
		// Ungültiger Feed
		if(!$manager->existObjectForID($feedID)) return;
		// Feed laden
		$feed = $manager->getObjectForID($feedID);
		
		// Feed als gelesen markieren
		$feed->markAsRead($before);
	}
	
	/**
	* Markiert eine Gruppe als gelesen.
	*
	* @param int $groupID
	* @param int $before
	**/
	private function markGroupAsRead($groupID, $before) {
		// Gruppen ID = 0?
		if(!$groupID) return $this->markAllAsRead($before);
	
		// Feed-Manager
		$manager = \Data\Group\Manager::main();
		// Alle Feeds laden
		$manager->loadAll();
		
		// Ungültiger Feed
		if(!$manager->existObjectForID($feedID)) return;
		// Feed laden
		$group = $manager->getObjectForID($feedID);
		
		// Feed als gelesen markieren
		$group->markAsRead($before);
	}
	
	/**
	* Markiert alle als gelesen.
	*
	* @param int $before
	**/
	private function markAllAsRead($before) {
		// Feed-Manager
		$manager = \Data\Feed\Manager::main();
		// Alle Feeds laden
		$manager->loadAll();
		
		// Alle Feeds durchlaufen
		foreach($manager as $feed)
			$feed->markAsRead($before);
	}
}
?>