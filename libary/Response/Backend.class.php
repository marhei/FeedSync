<?php
/**
* Stellt ein Mini-Backend für die Funktionen, die über die API nicht möglich sind, zur Verfügung
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/FeedSync/FeedSync
* @date 2013-05-08
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Response;

class Backend {
	/**
	* Fügt eine HTTP-Authentifizierung hinzu.
	**/
	public function __construct() {
		// Header-Instanz laden
		$header = \Core\Header::main();
		
		// Eine Authentifzierung hinzufügen
		$header->addAuthentication(	'Du musst dich mit deiner E-Mail-Adresse und deinem Passwort einlogen.',
									array(\Config\User\MAIL),
									array(\Config\User\PASSWORD));
	}
}
?>