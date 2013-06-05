<?php
/**
* Klasse, die die Readability-Parse-API verwendet.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/marhei/FeedSync
* @date 2013-06-03
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Core;

class Readability {
	const PARSE_URL = 'https://readability.com/api/content/v1/parser';
	
	private $responseObject;
	
	/**
	* Nimmt eine Artikel-URL entgegen und parst sie.
	*
	* @param string $url
	**/
	public function __construct($url) {
		// URL basteln
		$parseURL = self::PARSE_URL.'?url='.urlencode($url).'&token='.\Config\Readability\PARSE_TOKEN;
		// Anfrage stellen
		$this->responseObject = json_decode(file_get_contents($parseURL));
		
		// Fehler aufgetreten?
		if(isset($this->responseObject->error) && $this->responseObject->error)
			throw new Readability\Exception((string) $this->responseObject->message);
	}
	
	/**
	* Gibt den geparsten Inhalt zurück.
	*
	* @return string
	**/
	public function getContent() {
		return (string) $this->content;
	}
	
	/**
	* Gibt zurück, ob die Readability-API funktioniert. (Parse-Token valid?)
	*
	* @return bool
	**/
	public static function isValid() {
		try {
			// Projektseite an Readability senden
			new self(\Config\SITE);
			// Alles oaky
			return true;
		} catch(\Exception $exception) {
			// Fehler aufgetreten
			return false;
		}
	}
}
?>