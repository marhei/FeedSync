<?php
/**
* Hauptdatei, die alle weiteren Funktionen aufruft und benötigte Dateien einbindet.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/FeedSync/FeedSync
* @date 2013-05-07
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/

class Main {
	private $response = [];
	
	/**
	* Öffnet das Main-Array und authentifiziert den Benutzer.
	**/
	public function __construct() {
		// Benutzer authentifizieren…
		$this->authenticate();
		
		$this->response['last_refreshed_on_time'] = 0;
	}
	
	/**
	* Gibt den Inhalt des Response-Arrays als JSON aus.
	**/
	public function __destruct() {
		echo json_encode($this->response);
	}
	
	/**
	* Überprüft die Anmeldung für die API.
	**/
	private function authenticate() {
		// Auth-Keys vergleichen. Wenn falsch -> abbruch
		if(self::getAPIKey() != Request::POST('api_key')) exit;
		
		// Authentifizierungs-Informationen der Antwort hinzufügen
		$this->response['api_version'] = \Config\API_VERSION;
		$this->response['auth'] = true;
	}
	
	/**
	* Macht aus E-Mail-Adresse und Passwort den API-Key
	*
	* @return string
	**/
	public static function getAPIKey() {
		// Siehe http://www.feedafever.com/api
		return md5(\Config\User\MAIL.':'.\Config\User\PASSWORD);
	}
}
?>