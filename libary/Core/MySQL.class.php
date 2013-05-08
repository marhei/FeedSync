<?php
/**
* Eine zweite Version der MySQL-Klasse, die PDO verwendet um die Datenbank zu verwalten.
* Query-Objekte sind Instanzen der Klasse \Core\MySQL\Query.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/FeedSync/FeedSync
* @date 2013-02-04
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package McF Framework
**/
namespace Core;

class MySQL {
	private $pdoInstance;
	
	/**
	* Gibt die Haupt-MySQL-Verbindung zurück.
	*
	* @return MySQL
	**/
	public static function main() {
		// Statische Variable für die Hauptinstanz
		static $main = NULL;
		
		// MySQL-Instanz mit Konfig-Daten erstellen
		$main = $main ?: new self(	\Config\MySQL\SERVER,
									\Config\MySQL\USER,
									\Config\MySQL\PASSWORD,
									\Config\MySQL\DATABASE);
									
		// MySQL-Instanz zurückgeben
		return $main;
	}
	
	/**
	* Öffnet eine MySQL-Verbindung.
	*
	* @param string $server
	* @param string $user
	* @param string $password
	* @param string $database
	**/
	public function __construct($server, $user, $password, $database) {	
		try {
			// Verbindung mit PDO aufbauen
			$this->pdoInstance = new \PDO('mysql:host='.$server, $user, $password);	
			// Exceptions einschalten
			$this->pdoInstance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			
			// Datenbank auswählen, falls das gewünscht ist
			if(!is_null($database)) $this->selectDatabase($database);
		} catch(\PDOException $exception) {
			throw new MySQL\Exception('Die Verbindung mit dem MySQL-Server ist fehlgeschlagen.', 1060, $exception);
		}
	}
	
	/**
	* Wählt eine Datenbank aus.
	*
	* @param string $database - Name der Datenbank
	**/
	public function selectDatabase($database) {
		try {
			// SQL-Befehl senden
			$this->query('USE '.$database);
		} catch(MySQL\Exception $exception) {
			throw new MySQL\Exception('Die Datenbank „'.$database.'“ konnte nicht ausgewählt werden. Existiert sie überhaupt?', 1061, $exception);
		}
	}
	
	/**
	* Erzeugt eine neue Datenbank mit angegebenen Namen.
	*
	* @param string $name
	**/
	public function createDatabase($name) {
		try {
			// SQL-Befehl senden
			$this->query('CREATE DATABASE IF NOT EXISTS '.$name);
		} catch(MySQL\Exception $exception) {
			throw new MySQL\Exception('Die Datenbank „'.$name.'“ konnte nicht erstellt werden.', 1062, $exception);
		}
	}
	
	/**
	* Escapt Strings für eine Query
	*
	* @param string $string
	* @return string
	**/
	public function quoteString($string) {
		return $this->pdoInstance->quote($string);
	}
	
	/**
	* Gibt ein Table-Action-Objekt zu einer Tabelle zurück.
	*
	* @param string $table
	* @return MySQL\Table
	**/
	public function tableActions($table) {
		return new MySQL\Table($this, $table);
	}
	
	/**
	* Schickt eine Query an den Server und gibt ein Query-Objekt zurück.
	*
	* @param string $sql
	* @return PDOStatement
	**/
	public function query($sql) {
		try {
			// Query an Server senden
			return $this->pdoInstance->query($sql);
		} catch(\PDOException $exception) {
			throw new MySQL\Exception('Beim Ausführen der Query ist ein Fehler aufgetreten.', 1063, $exception);
		}
	}
	
	/**
	* Schickt mehrere Queries an den MySQL-Server. (Keine Rückgabe
	*
	* @param string $sql
	**/
	public function queries($sql) {
		// Queries spalten
		$queries = preg_split("/;+(?=([^'|^\\\']*['|\\\'][^'|^\\\']*['|\\\'])*[^'|^\\\']*[^'|^\\\']$)/", $sql);
		
		// Alle Queries einzeln durchgehen
		foreach($queries as $currentQuery) {
			if(strlen(trim($currentQuery)) == 0) continue;
			
			// Query an Server senden
			$this->query($currentQuery);
		}
	}
	
	/**
	* Gibt die zuletzt eingefügte ID zurück.
	*
	* @return int
	**/
	public function getLastID() {
		return $this->pdoInstance->lastInsertID();
	}
}
?>