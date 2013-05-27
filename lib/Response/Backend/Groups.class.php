<?php
/**
* Gruppen-Module für das Backend.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/marhei/FeedSync
* @date 2013-05-27
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Response\Backend;

class Groups {
	private $manager, $feedManager;

	/**
	* Konstruktor, der definiert, das ein Template aufgerufen werden soll.
	**/
	public function __construct() {
		// Template definieren
		\Response\Backend::setModuleVar('template', 'groups');
		
		// Manager laden
		$this->manager = \Data\Group\Manager::main();
		// Alle Gruppen laden
		$this->manager->loadAll();
		// Manager dem Modul hinzufügen
		\Response\Backend::setModuleVar('manager', $this->manager);
		
		// Feed-Manager laden
		$this->feedManager = \Data\Feed\Manager::main();
		// Alle Feeds laden
		$this->feedManager->loadAll();
		// Manager dem Modul hinzufügen
		\Response\Backend::setModuleVar('feedManager', $this->feedManager);
		
		try {
			// Gruppe löschen
			if(\Core\Request::GET('deleteGroup', false)) $this->deleteGroup();
			// Gruppe hinzufügen
			if(\Core\Request::GET('addGroup', false)) $this->addGroup();
			
			// Gruppenbeziehungen speichern
			if(\Core\Request::GET('changeGroups', false)) $this->changeGroups();
		} catch(\Exception $exception) {
			\Response\Backend::setModuleVar('error', $exception->getMessage());
		}
	}
	
	/**
	* Fügt eine Gruppe hinzu.
	**/
	private function addGroup() {
		// Gruppenname laden
		$groupName = \Core\Request::POST('groupName');
		// Feed-Objekt erstellen
		$groupObject = new \Data\Group($groupName);
			
		// Feed-Objekt dem Manager hinzufügen
		$this->manager->addObject($groupObject);
	}
	
	/**
	* Löscht eine Gruppe.
	**/
	private function deleteGroup() {
		// Gruppen-ID laden
		$groupID = \Core\Request::GET('groupID', -1);		
		// Löschen
		$this->manager->removeObject($groupID);
	}
	
	/**
	* Ändert die Gruppenbeziehungen
	**/
	private function changeGroups() {
		// Neue Beziehungen laden
		$groupRelationships = \Core\Request::POST('groupRelationships', array());
		
		// Alle Gruppen durchlaufen
		foreach($this->manager as $id => $currentGroup) {
			// Gruppe nicht vorhanden?
			if(!isset($groupRelationships[$id])) continue;
			
			// Neue Beziehungen setzen
			$currentGroup->getRelationship()->setFeedIDs($groupRelationships[$id]);
		}
	}
}
?>