<?php
/**
* Klasse zum Umwandeln von Daten in das XML-Format.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/marhei/FeedSync
* @date 2013-05-20
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Core;

class XML {
	private $data, $container;
	
	/**
	* Nimmt die Daten an, die umgewandelt werden sollen.
	*
	* @param mixed $data
	**/
	public function __construct($data, $container) {
		// Daten umwandeln
		$data = self::convertData($data);
		// Daten in der Klasse speichern
		$this->data = $data;
		
		// Container in der Klasse speichern
		$this->container = $container;
	}
	
	/**
	* Gibt die umgewandelten Daten als XML-Wert zurück.
	*
	* @return string
	**/
	public function __toString() {
		return $this->toXML($this->data);
	}
	
	/**
	* Wandelt ein Array um in ein XML-Wert.
	*
	* @param array $array
	* @param bool $root [optional]
	* @param string $lastContainer [optional]
	**/
	private function toXML(array $array, $root = true, $lastContainer = '') {
		// Puffervariable
		$xml = '';
	
		// Root-Element -> XML-Kopf einfügen
		if($root) {
			$xml .= '<?xml version="1.0" encoding="utf-8"?>';
			$xml .= '<'.$this->container.'>';
		}
		
		// Was als Element nehmen?	
		$otherContainer = array_keys($array) === array_keys(array_keys($array));
		// Array durchlaufen
		foreach($array as $key => $value) {
			// Anderen Container verwenden? Letzten Container ohne Plural-S verwenden
			if($otherContainer)
				$key = preg_replace('/s$/', '', $lastContainer);
		
			// Element hinzufügen
			$xml .= '<'.$key.'>';
			
			// Mehr Array?
			if(is_array($value))
				$xml .= $this->toXML($value, false, $key);
			else // Kein Array, Inhalt direkt hinzufügen
				$xml .= (htmlspecialchars($value, ENT_COMPAT, 'ISO-8859-1') != $value) ? "<![CDATA[{$value}]]>" : $value;
			
			// Element beenden
			$xml .= '<'.$key.'>';
		}
		
		// Root-Element -> Ende anhängen
		if($root)
			$xml .= '</'.$this->container.'>';
			
		return $xml;
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
	    	if(!$data instanceof XML\Serializable)
	    		throw new XML\Exception('Es wird versucht ein nicht serialisierbares Objekt zu serialisieren');
	    	
	    	// Daten umwandeln
	    	$data = $data->xmlSerialize();
	    }
		
		// Daten zurückgeben
		return $data;
	}
}
?>