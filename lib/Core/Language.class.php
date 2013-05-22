<?php
/**
* Die Klasse hilft Sprachdateien zu verwalten.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/marhei/FeedSync
* @date 2013-05-22
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Core;

class Language {
	private $lang, $content;
	
	/**
	* Gibt die Haupt-Sprachinstanz zurück.
	*
	* @return Language
	**/
	public static function main() {
		// Statische Variable für die Hauptinstanz
		static $main = NULL;
		
		// Sprach-Instanz mit Konfig-Daten erstellen
		$main = $main ?: new self(\Config\LANGUAGE);
									
		// Sprach-Instanz zurückgeben
		return $main;
	}
	
	/**
	* Öffnet eine neue Instanz der Sprach-Klasse.
	*
	* @param string $lang - Sprache
	**/
	public function __construct($lang) {
		// Sprache in der Klasse speichern
		$this->lang = $lang;
		// Inhalt der INI-Datei laden
		$this->loadFile();
	}
	
	/**
	* Lädt die Sprache.
	**/
	private function loadFile() {
		// Name der INI-Datei
		$name = LANGUAGE_PATH.$this->lang.'.ini';
		// Existiert die Datei?
		if(!file_exists($name)) throw new \Exception('Unbekannte Sprache ausgewählt. / Unknown language chosen.', 1);
		
		// Inhalt auslesen
		$this->content = parse_ini_file($name, true);
	}
	
	/**
	* Gibt die richtige Übersetzung zurück.
	*
	* @param string $group - Übersetzungsgruppe
	* @param string $object - Übersetzungobjekt
	* @param array $formatArgs - Formatierungs-Argumente für sprintf
	**/
	public function get($group, $object, array $formatArgs = array()) {
		// Wert laden
		$content = $this->content[$group][$object];
		
		// Argumente bilden
		$args = array($content) + $formatArgs;
		// sprintf aufrufen
		return call_user_func_array('sprintf', $args);
	}
}
?>