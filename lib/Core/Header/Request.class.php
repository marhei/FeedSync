<?php
/**
* Aufrufen von URLs mit speziellen HTTP-Headern.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/marhei/FeedSync
* @date 2013-05-29
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Core\Header;

class Request {
	private $url, $headers, $authData;
	
	/**
	* Nimmt die URL an und erstelt eine Instanz der Klasse.
	*
	* @param string $url
	**/
	public function __construct($url) {
		$this->url = $url;
		
		// Header speichern
		$this->headers = get_headers($url, true);
	}
	
	/**
	* Gibt die URL zurück.
	*
	* @return string
	**/
	public function getURL() {
		return $this->url;
	}
	
	/**
	* Authentifizierung notwendig?
	*
	* @return bool
	**/
	public function needAuthentication() {
		return in_array('HTTP/1.0 401 Unauthorized', $this->headers);
	}
	
	/**
	* Gibt die Art der Authentifizierung zurück.
	*
	* @return string
	**/
	public function getAuthenticationType() {
		// Passender Header-String
		$auth = $this->headers['WWW-Authenticate'];
		// Aufteilen
		$auth = explode(' ', $auth);
		
		// Erster Teil zurückgeben
		return $auth[0];
	}
	
	/**
	* Gibt den Text der Authentifizierung zurück.
	*
	* @return string
	**/
	public function getAuthenticationRealm() {
		// Passender Header-String
		$auth = $this->headers['WWW-Authenticate'];
		// Pregmatch machen
		preg_match("/realm=\"(.*)\"$/", $auth, $matches);
		
		// Realm zurückgeben
		return $matches[1];
	}
	
	/**
	* Setzt ein Passwort und einen Nutzername.
	*
	* @param string $user
	* @param string $pass
	**/
	public function setAuthentication($user, $pass) {
		// String basteln
		$this->authData = base64_encode($user.':'.$pass);
	}
	
	/**
	* Gibt den Inhalt der URL zurück.
	*
	* @return string
	**/
	public function getContent() {
		// Headerarray basteln
		$header = array(
			'http' => array(
				'method' => 'GET',
				'header' => 'Authorization: Basic '.$this->authData
			)
		);
		// Stream erstellen
		$context = stream_context_create($header);
		
		// Inhalt zurückgeben
		return file_get_contents($this->url, false, $context);
	}
}
?>