<?php
/**
* Eine RSS-Feed, der ein Favicon, ein Title, eine URL, eine Seiten-URL und das letzte Update beinhaltet.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/FeedSync/FeedSync
* @date 2013-05-08
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Data;

class Feed implements \JsonSerializable, \Core\Manager\Identable {
	private $id, $faviconID, $title, $url, $siteURL, $lastUpdate;
	
	/**
	* Gibt die Rückgabewerte für die API zurück.
	*
	* @return array
	**/
	public function jsonSerialize() {
		return array(	'id'					=> $this->id,
						'favicon_id'			=> $this->faviconID,
						'title'					=> $this->title,
						'url'					=> $this->url,
						'site_url'				=> $this->siteURL,
						'is_spark'				=> false,
						'last_updated_on_time'	=> $this->lastUpdate);
	}
	
	/**
	* Erstellt einen neuen Feed.
	*
	* @param string $title - Name der Gruppe.
	**/
	public function __construct($url) {
		// Hier kommt noch Feed-Magie hin.
	}
	
	/**
	* Gibt die ID des Feeds zurück.
	*
	* @return int
	**/
	public function getID() {
		return $this->id;
	}
	
	/**
	* Setzt die ID des Feeds.
	*
	* @param int $id
	**/
	public function setID($id) {
		$this->id = $id;
	}
}
?>