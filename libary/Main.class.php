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
	/**
	* Entscheidet, welche Klasse geöffnet wird.
	**/
	public function __construct() {
		// API-Aufruf? Klasse instanzieren.
		if(\Core\Request::GET('api',false)!==false) {
			// Was für eine Antwort wurde angefordert?
			new API(\Core\Request::GET('api') ?: API::JSON);
		} else {
			
		}
	}
}
?>