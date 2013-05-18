<?php
/**
* Diese Klasse verwaltet alles Anfragen an das Skript.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/FeedSync/FeedSync
* @date 2013-05-07
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Core;

class Request {
	/**
	* Element aus den mitgeschickten POST-Daten laden.
	*
	* @param string $key - Der Key
	* @param mixed $default - Standardwert [optional]
	* @return mixed
	**/
	public static function POST($key, $default=NULL) {
		// Wenn Key vorhanden zurückgeben, sonst Standardwert
		return isset($_POST[$key]) ? $_POST[$key] : $default;
	}
	
	/**
	* Gibt zurück, ob ein POST-Element gesetzt ist.
	*
	* @param string $key
	* @return bool
	**/
	public static function issetPOST($key) {
		return isset($_POST[$key]);
	}
	
	/**
	* Element aus den mitgeschickten GET-Daten laden.
	*
	* @param string $key - Der Key
	* @param mixed $default - Standardwert [optional]
	* @return mixed
	**/
	public static function GET($key, $default=NULL) {
		// Wenn Key vorhanden zurückgeben, sonst Standardwert
		return isset($_GET[$key]) ? $_GET[$key] : $default;
	}
	
	/**
	* Gibt zurück, ob ein GET-Element gesetzt ist.
	*
	* @param string $key
	* @return bool
	**/
	public static function issetGET($key) {
		return isset($_GET[$key]);
	}
}
?>