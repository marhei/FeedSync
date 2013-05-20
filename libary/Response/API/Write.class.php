<?php
/**
* Zum schreiben von Informationen über Items.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/FeedSync/FeedSync
* @date 2013-05-18
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Response\API;

class Write {
	private $apiInstance, $manager;
	
	/**
	* Gibt der API-Datenklasse die API-Instanz mit und führt dann alles weiter aus.
	*
	* @param \Response\API $apiInstance
	**/
	public function __construct(\Response\API $apiInstance) {
		// Instanz in der Klasse speichern
		$this->apiInstance = $apiInstance;
		// Manager speichern
		$this->manager = \Data\Item\Manager::main();
		
		// Daten auslesen
		$type = \Core\Request::POST('mark');
		$id = \Core\Request::POST('id');
		$as = \Core\Request::POST('as');
		
		// Was soll markiert werden?
		switch($type) {
			case 'item':
				$this->markItemAs($id, $as);
				break;
		}
	}
	
	/**
	* Markiert ein Item als wasauchimmer.
	*
	* @param int $id
	* @param string $as
	**/
	private function markItemAs($id, $as) {
		// Item laden
		$this->manager->loadIDs(array($id));
		// Item nicht vorhanden? Abbruch!
		if(!$this->manager->existObjectForID($id)) return;
		// Item fetchen
		$item = $this->manager->getObjectForID($id);
		
		// Operation auswählen
		switch($as) {
			case 'read':
				$item->getAction()->setRead();
				break;
			case 'unread':
				$item->getAction()->setUnread();
				break;
			case 'saved':
				$item->getAction()->setSaved();
				break;
			case 'unsaved';
				$item->getAction()->setUnsaved();
				break;
		}
	}
}
?>