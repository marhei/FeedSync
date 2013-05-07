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

/**
* Wichtige Konstanten
**/
define('ROOT_PATH', __DIR__.'/');
define('LIBARY_PATH', ROOT_PATH.'libary/');
define('CONFIG_PATH', ROOT_PATH.'config.inc.php');

/**
* Fehlerbehandlung
**/
set_error_handler(function($errorNo, $errorString, $errorFile, $errorLine) {
	// Fehler als Exception werfen
	throw new \ErrorException($errorString, 0, $errorNo, $errorFile, $errorLine);
}, E_ALL);

/**
* Autoloading von Klassen
**/
spl_autoload_register(function($classname) {
	// Dateinamen basteln
	$filename = LIBARY_PATH.str_replace('\\', '/', $classname).'.class.php';
	
	// Wenn die Datei existiert diese auch einbinden
	if(file_exists($filename)) require_once $filename;
});

/**
* Konfiguration einbinden
**/
require_once CONFIG_PATH;

/**
* Programm starten
**/
new \Main();
?>