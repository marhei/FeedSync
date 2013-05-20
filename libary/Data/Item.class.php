<?php
/**
* Ein Item aus dem RSS-Feed.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/FeedSync/FeedSync
* @date 2013-05-10
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Data;

class Item implements \Core\JSON\Serializable, \Core\Manager\Indentable {
	private $id, $feedID, $title, $author, $html, $url, $createTime, $action;
	
	/**
	* Gibt die Rückgabewerte für die API zurück.
	*
	* @return array
	**/
	public function jsonSerialize() {
		return array(	'id'				=> $this->id,
						'feed_id'			=> $this->feedID,
						'title'				=> $this->title,
						'author'			=> $this->author,
						'html'				=> $this->html,
						'url'				=> $this->url,
						'is_saved'			=> $this->action->isSaved(),
						'is_read'			=> $this->action->isRead(),
						'created_on_time'	=> $this->createTime);
	}
	
	/**
	* Beim Klonen auch das Actiondings klonen. (Damit der Manager einen Unterschied erkennen kann.
	**/
	public function __clone() {
		$this->action = clone $this->action;
	}
	
	/**
	* Erzeugt ein neues Item.
	*
	* @param \Data\Feed $feed
	* @param string $title
	* @param string $author
	* @param string $html
	* @param string $url
	* @param string $createTime
	**/
	public function __construct(\Data\Feed $feed, $title, $author, $html, $url, $createTime) {
		// ID speichern
		$this->feedID = $feed->getID();
		
		// Daten speichern
		$this->title = $title;
		$this->author = $author;
		$this->html = $html;
		$this->url = $url;
		$this->createTime = $createTime;
		
		// Action-Instanz erstellen
		$this->action = new Item\Action();
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
	}
	
	/**
	* Gibt die ID des Feeds zurück.
	*
	* @return string
	**/
	public function getFeedID() {
		return $this->feedID;
	}
	
	/**
	* Gibt den Namen des Items zurück.
	*
	* @return string
	**/
	public function getTitle() {
		return $this->title;
	}
	
	/**
	* Gibt den Autor des Items zurück.
	*
	* @return string
	**/
	public function getAuthor() {
		return $this->author;
	}
	
	/**
	* Gibt den HTML-Inhalt des Items zurück.
	*
	* @return string
	**/
	public function getHTML() {
		return $this->html;
	}
	
	/**
	* Gibt die URL des Items zurück.
	*
	* @return string
	**/
	public function getURL() {
		return $this->url;
	}
	
	/**
	* Gibt das Erstellerdatum des Items zurück.
	*
	* @return string
	**/
	public function getCreateTime() {
		return $this->createTime;
	}
	
	/**
	* Gibt die Aktion-Instanz zurück.
	*
	* @return Item\Action
	**/
	public function getAction() {
		return $this->action;
	}
}
?>