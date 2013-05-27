<?php
/**
* Stellt die API zur Verfügung. Für mehr Informationen, siehe http://www.feedafever.com/api
* Es wurde zusätzlich einige Zusatzinformationen hinzugefügt:
*	- „feedsync“ gibt immer true zurück.
*	- „feedsync_version“ gibt die Version von FeedSync zurück.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/marhei/FeedSync
* @date 2013-05-08
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Response;

class API {
	const JSON = 'json';
	const XML = 'xml';

	private $response = array(), $responseType;
	
	/**
	* Öffnet das Main-Array und authentifiziert den Benutzer.
	*
	* @param string $responseType - Wie soll die API reagieren? [optional]
	**/
	public function __construct($responseType = self::JSON) {
		$this->responseType = $responseType;
		// Benutzer authentifizieren…
		$this->authenticate();
		
		// API-Version senden
		$this->response['api_version'] = \Config\API_VERSION;
		// Mitschicken, dass die API von FeedSync zur Verfügung gestellt wird
		$this->response['feedsync'] = true;
		// Die Version von FeedSync hinzufügen
		$this->response['feedsync_version'] = \Config\VERSION;
		
		// Die Daten zurückgeben
		new API\Data($this);
		// Sync zur Verfügung stellen
		new API\Sync($this);
		// Datenänderungen aufnehmen
		new API\Write($this);
	}
	
	/**
	* Gibt den Inhalt des Response-Arrays als JSON aus.
	**/
	public function __destruct() {
		if($this->responseType == self::JSON) { // JSON-Antwort erwünscht?
			// Entsprechenden Header senden
			\Core\Header::main()->setContentType('application/json');
			// JSON-Daten zurückgeben
			$output = new \Core\JSON($this->response);
		} else if($this->responseType == self::XML) { // XML-Antwort?
			// Entsprechenden Header senden
			\Core\Header::main()->setContentType('application/xml');
			// XML-Daten zurückgeben
			$output = new \Core\XML($this->response, 'response');
		}
		
		// Ausgabe
		echo $output;
	}
	
	/**
	* Überprüft die Anmeldung für die API.
	**/
	private function authenticate() {
		// Auth-Keys vergleichen. Wenn falsch -> abbruch
		if(self::getKey() != \Core\Request::POST('api_key')) {
			// Authentifizierung fehlgeschalgen
			$this->response['auth'] = false;
			// Abbruch des weiteren Skripts
			exit;
		} else $this->response['auth'] = true;
	}
	
	/**
	* Fügt etwas der Rückgabe hinzu.
	*
	* @param string $key
	* @param mixed $content
	**/
	public function addResponse($key, $content) {
		$this->response[$key] = $content;
	}
	
	/**
	* Macht aus E-Mail-Adresse und Passwort den API-Key
	*
	* @return string
	**/
	public static function getKey() {
		// Berechneter API-Key
		return md5(\Config\User\MAIL.':'.\Config\User\PASSWORD);
	}
}
?>