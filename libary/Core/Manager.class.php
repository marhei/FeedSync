<?php
/**
* Verwaltet Objekte, die in einer Datenbank gespeichert sind.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/FeedSync/FeedSync
* @date 2012-12-12
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package McF Framework
**/
namespace Core;

abstract class Manager implements \Countable, \IteratorAggregate {
	protected $objects = array(), $unchangedObjects = array(), $tableActions;
	
	/**
	* Gibt den Haupt-Manager zurück.
	*
	* @return Manager
	**/
	public static function main() {
		// Header-Instanz erstellen
		static::$mainInstance = static::$mainInstance ?: new static();
									
		// Header-Instanz zurückgeben
		return static::$mainInstance;
	}
	
	/**
	* Bereitet die Datenbank-Tabelle vor.
	**/
	public function __construct() {
		// Table-Action-Instanz setzen
		$this->tableActions = MySQL::main()->tableActions(static::TABLE);
	}
	
	/**
	* Speichert alle geänderten Objekte in der Datenbank
	**/
	public function __destruct() {
		// Alle geladenen Objekte durchlaufen
		foreach($this->objects as $objectID => $currentObject) {
			// Aktuelles Objekt mit dem Ursprungszustand vergleichen
			if($currentObject == $this->unchangedObjects[$objectID]) continue;
			
			// Daten, die in der DB geändert werden müssen vergleichen
			$contentArray = $this->getContentArrayForObject($currentObject);
			// Änderung durchführen
			$this->tableActions->update($contentArray, array('id'=>$objectID));
		}
	}
	
	/**
	* Gibt das Content-Array für ein Objekt zurück.
	*
	* @param object $object - Das Objekt
	* @return array - Content-Array
	**/
	abstract protected function getContentArrayForObject($object);
	
	/**
	* Lädt alle Objekte aus der Datenbank
	**/
	public function loadAll() {
		// Statisch Varible zu cachen
		static $loaded = false;
		// Bereits geladen? Abbruch!
		if($loaded) return;
		// Merken, dass es nun geladen ist
		$loaded = true;
	
		// Alle Objekte laden
		$queryObject = $this->tableActions->select();
		// Zu dem Manager hinzufügen
		$this->saveInInstance($queryObject);
	}
	
	/**
	* Speichert die geladenen Objekte in dieser Instanz
	*
	* @param PDOStatement $queryObject - Die MySQL-Anfrage
	**/
	protected function saveInInstance(\PDOStatement $queryObject) {
		// Query durchlaufen
		foreach($queryObject as $currentRow) {
			// Ist dieses Element bereits im Manager?
			if($this->existObjectForID($currentRow['id'])) continue;
			
			// Objekt entserialisieren
			$newObject = unserialize($currentRow['object']);
			
			// Objekt dem Array hinzufügen
			$this->objects[$currentRow['id']] = $newObject;
			// Geclontes Objekt dem Array hinzufügen (Für späteren Vergleich.)
			$this->unchangedObjects[$currentRow['id']] = clone $newObject;
		}
	}
	
	/**
	* Gibt die Anzahl der Objekte zurück.
	*
	* @return int
	**/
	public function count() {
		return count($this->objects);
	}
	
	/**
	* Zählt alle vorhandenen Objekte
	*
	* @return int - Anzahl
	**/
	public function countAll() {
		return $this->tableActions->count();
	}
	
	/**
	* Gibt die Iterator-Instanz zurück.
	*
	* @return \ArrayIterator
	**/
	public function getIterator() {
		return new \ArrayIterator($this->objects);
	}
	
	/**
	* Gibt es ein Objekt mit dieser ID?
	*
	* @param int $taskID - ID
	* @return bool - Ja/Nein
	**/
	public function existObjectForID($objectID) {
		return isset($this->objects[$objectID]);
	}
	
	/**
	* Gibt ein Objekt mit einer bestimmten ID zurück.
	*
	* @param int $objectID - Die ID.
	* @return object - Die Ausschreibung
	**/
	public function getObjectForID($objectID) {
		// Existiert dieses Objekt überhaupt?
		if(!$this->existObjectForID($objectID)) throw new \Exception('Ein Objekt mit dieser ID existiert nicht.', 1);
		
		return $this->objects[$objectID];
	}
	
	/**
	* Fügt ein neues Objekt hinzu.
	*
	* @param object $task - Das neue Objekt
	**/
	public function addObject($object) {	
		// Was muss dafür in die DB geschrieben werden?
		$contentArray = $this->getContentArrayForObject($object);
		
		// Es in die DB schreiben
		$queryObject = $this->tableActions->insert($contentArray);
		// Die letzte ID ermitteln
		$objectID = MySQL::main()->getLastID();
		
		// ID dem Objekt hinzufügen, falls es ein Identable-Objekt ist.
		if($object instanceof Manager\Identable) $object->setID($objectID);
		
		// Objekt dem Array hinzufügen
		$this->objects[$objectID] = $object;
		// Objekt zum Vergleich klonen
		$this->unchangedObjects[$objectID] = clone $object;
	}
	
	/**
	* Löscht ein Objekt aus der Datenbank.
	*
	* @param int $objectID - ID
	**/
	public function removeObject($objectID) {
		// Aus der DB löschen
		$this->tableActions->delete(array('id'=>$objectID));
		
		// Einträge in der Klasse löschen
		unset($this->objects[$objectID]);
		unset($this->unchangedObjects[$objectID]);
	}
}