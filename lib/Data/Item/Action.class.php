<?php
/**
* Stellt Actions für ein Item zur Verfügung.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/marhei/FeedSync
* @date 2013-05-18
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Data\Item;

class Action {
	private $saved = false, $read = false;
	
	/**
	* Gibt zurück, ob das Item, gespeichert ist.
	*
	* @return bool
	**/
	public function isSaved() {
		return $this->saved;
	}
	
	/**
	* Setzt das Item als gespeichert.
	**/
	public function setSaved() {
		$this->saved = true;
	}
	
	/**
	* Setzt das Item als nicht gespeichert.
	**/
	public function setUnsaved() {
		$this->saved = false;
	}
	
	/**
	* Gibt zurück, ob das Item gelesen ist.
	*
	* @return bool
	**/
	public function isRead() {
		return $this->read;
	}
	
	/**
	* Setzt das Item als gelesen.
	**/
	public function setRead() {
		$this->read = true;
	}
	
	/**
	* Setzt das Item als ungelesen.
	**/
	public function setUnread() {
		$this->read = false;
	}
}
?>