<?php
/**
* Eine RSS-Gruppe, die eine Namen besitzt
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/FeedSync/FeedSync
* @date 2013-05-08
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Data;

class Group implements \JsonSerializable, \Core\Manager\Indentable {
	private $id, $title, $relationship;
	
	/**
	* Gibt die Rückgabewerte für die API zurück.
	*
	* @return array
	**/
	public function jsonSerialize() {
		return array(	'id'	=> $this->id,
						'title'	=> $this->title);
	}
	
	/**
	* Erstellt eine neue Gruppe.
	*
	* @param string $title - Name der Gruppe.
	**/
	public function __construct($title) {
		$this->title = $title;
		
		// Neue Beziehung erstellen
		$this->relationship = new Group\Relationship();
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
		
		// Auch ID der Beziehung ädern
		$this->relationship->setID($id);
	}
	
	/**
	* Gibt den Namen der Gruppe zurück.
	*
	* @return string
	**/
	public function getTitle() {
		return $this->title;
	}
	
	/**
	* Setzt den Title.
	*
	* @param string $title
	**/
	public function setTitle($title) {
		$this->title = $title;
	}
	
	/**
	* Gibt die Beziehung zurück.
	*
	* @return Group\Relationship
	**/
	public function getRelationship() {
		return $this->relationship;
	}
}
?>