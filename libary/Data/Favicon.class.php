<?php
/**
* Das Icon eines Feeds. Verwendet den Google-S2-Service.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/FeedSync/FeedSync
* @date 2013-05-10
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Data;

class Favicon implements \JsonSerializable, \Core\Manager\Indentable {
	const SERVICE_URL = 'http://www.google.com/s2/favicons?domain=';
	const SERVICE_RESPONSE = 'image/png';

	private $id, $data;
	
	/**
	* Gibt die Rückgabewerte für die API zurück.
	*
	* @return array
	**/
	public function jsonSerialize() {
		return array(	'id'	=> $this->id,
						'data'	=> $this->data);
	}
	
	/**
	* Erstellt ein neues Favicon.
	*
	* @param string $url - URL des Feeds
	**/
	public function __construct($url) {
		// URL zum S2-Service basteln
		$faviconURL = self::SERVICE_URL.urlencode($url);
		
		// Daten auslesen
		$faviconData = file_get_contents($faviconURL);
		// Daten enkodieren
		$faviconData = base64_encode($faviconData);
		
		// Die Daten zusammen bauen
		$this->data = self::SERVICE_RESPONSE.';base64,'.$faviconData;
	}
	
	/**
	* Gibt die ID des Favicons zurück.
	*
	* @return int
	**/
	public function getID() {
		return $this->id;
	}
	
	/**
	* Setzt die ID des Favicons.
	*
	* @param int $id
	**/
	public function setID($id) {
		$this->id = $id;
	}
	
	/**
	* Gibt die Daten des Favicons zurück.
	*
	* @return string
	**/
	public function getData() {
		return $this->data;
	}
}
?>