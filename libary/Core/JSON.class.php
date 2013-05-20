<?php
/**
* Klasse zum Umwandeln von Daten in das JSON-Format.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/marhei/FeedSync
* @date 2013-05-17
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Core;

class JSON {
	private $data;
	
	/**
	* Nimmt die Daten an, die umgewandelt werden sollen.
	*
	* @param mixed $data
	**/
	public function __construct($data) {
		// Daten umwandeln
		$data = self::convertData($data);
		// Daten in der Klasse speichern
		$this->data = $data;
	}
	
	/**
	* Gibt die umgewandelten Daten als JSON-Wert zurück.
	*
	* @return string
	**/
	public function __toString() {
		return json_encode($this->data);
	}
	
	/**
	* Methode, die Daten in serialisibare Daten umwandelt.
	*
	* @param mixed $data
	* @return mixed
	**/
	private static function convertData($data) {
		// Array?
	    if(is_array($data)) {
	    	// Neues Array
	    	$array = array();
	    	
	    	// Daten durchlaufen
	    	foreach($data as $key => $value)
				$array[$key] = self::convertData($value);
				
			// Daten speichern
			$data = $array;
		} else if(is_object($data)) { // Objekt?
	    	// Nicht serialisierbar?
	    	if(!$data instanceof JSON\Serializable)
	    		throw new JSON\Exception('Es wird versucht ein nicht serialisierbares Objekt zu serialisieren');
	    	
	    	// Daten umwandeln
	    	$data = $data->jsonSerialize();
	    }
		
		// Daten zurückgeben
		return $data;
	}
}
?>