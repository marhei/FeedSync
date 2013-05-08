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

class Group {
	private $title;
	
	/**
	* Erstellt eine neue Gruppe.
	*
	* @param string $title - Name der Gruppe.
	**/
	public function __construct($title) {
		$this->title = $title;
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
}
?>