<?php
/**
* Gibt Rückgabewerte für den Sync von FeedSync.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/FeedSync/FeedSync
* @date 2013-05-18
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Response\API;

class Sync {
	private $apiInstance;
	
	/**
	* Gibt der API-Datenklasse die API-Instanz mit und führt dann alles weiter aus.
	*
	* @param \Response\API $apiInstance
	**/
	public function __construct(\Response\API $apiInstance) {
		// Instanz in der Klasse speichern
		$this->apiInstance = $apiInstance;
	}
}
?>