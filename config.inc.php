<?php
/**
* Konfigurations-Datei, die alle wichtigen Einstellungen beinhalten.
*	Für mehr Informationen bitte die README.md lesen.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/marhei/FeedSync
* @date 2013-05-07
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/

/**
* Allgemeine Konfigurationen
**/
namespace Config {
	// Versionsnummer des Skripts
	const VERSION = '0.9beta';
	// Welche API-Version spiegelt dieses Skript wider.
	const API_VERSION = 3;
	// Debug-Modus?
	const DEBUG = false;
}

/**
* MySQL-Konfigurationen
**/
namespace Config\MySQL {
	// MySQL-Server
	const SERVER = 'localhost';
	// MySQL-User
	const USER = 'root';
	// MySQL-Passwort
	const PASSWORD = 'root';
	// Datenbank
	const DATABASE = 'FeedSync';
}

/**
* Benutzer-Konfigurationen
**/
namespace Config\User {
	// E-Mail-Adresse
	const MAIL = 'test@mail.com';
	// Passwort
	const PASSWORD = 'password';
}
?>