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
namespace Core\XML;

class Writter extends XMLWriter {
	/**
	* Konstruktor, der das Erstellen von XML-Dokumenten vereinfachen soll.
	*
	* @param string $charset [optional]
	**/
	public function __construct($charset='utf-8') {
		// Memory öffnen
		$this->openMemory();
		// Dokument öffnen
		$this->startDocument('1.0', $charset);
	}
	
	/**
	* Schließt das Dokument
	**/
	public function __destruct() {
		// Dokument beenden
		$this->endDocument();
	}
	
	/**
	* Gibt ein XML auf dem Bildschirm aus.
	**/
	public function output() {
		// Dokument beenden
		$this->endDocument();
		// Inhalt ausgeben
		echo $this->outputMemory(true);
	}

}
?>