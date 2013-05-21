<?php
/**
* Hauptdatei, die alle weiteren Funktionen aufruft und benötigte Dateien einbindet.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/marhei/FeedSync
* @date 2013-05-07
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/

class Main {
	/**
	* Entscheidet, welche Klasse geöffnet wird.
	**/
	public function __construct() {	
		// Soll neugeladen werden?
		if(\Core\Request::issetGET('refresh')) $this->refresh();
	
		// Was wurde angefordert?
		if(\Core\Request::issetGET('api')) $this->callAPI();
		else $this->callBackend();
	}
	
	/**
	* Ruft die API auf.
	**/
	private function callAPI() {
		// Debug-Daten speichern
		if(\Config\DEBUG) $this->saveDebug();
		// Was für eine Antwort wurde angefordert?
		new \Response\API(\Core\Request::GET('api') ?: \Response\API::JSON);
	}
	
	/**
	* Ruft das Backend auf.
	**/
	private function callBackend() {
		// Mini-Backend zur Verfügung stellen
		new \Response\Backend();
	}
	
	/**
	* Aktuallisiert die RSS-Feeds.
	**/
	private function refresh() {
		// Alle RSS-Feeds abgleichen
		\Data\Feed\Manager::main()->updateAllItemLists();
	}
	
	/**
	* Speichert die DEBUG-Daten.
	**/
	private function saveDebug() {
		// POST-Daten speichern
		file_put_contents('post.txt', print_r($_POST, true), FILE_APPEND);
		// GET-Daten speichern
		file_put_contents('get.txt', print_r($_GET, true), FILE_APPEND);
	}
}
?>