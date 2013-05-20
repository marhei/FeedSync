<?php
/**
* XML-Element, mit einigen Zusatzfunktionen.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/marhei/CoreCMS
* @date 2013-01-02
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package McF Framework
**/
namespace Core\XML;

class Element extends \SimpleXMLElement {
	/**
	* Erstellt ein Element aus dem Inhalt der angebenen Datei.
	*
	* @param string $filename - Dateiname
	* @return self
	**/
	public static function loadFile($filename) {
		try {
			return simplexml_load_file($filename, __CLASS__);
		} catch (\Exception $exception) {
			throw new \Exception('Die angeforderte XML-Datei konnte nicht geladen werden.', 1, $exception);
		}
	}

	/**
	* Macht aus sich selbst ein Array. Rekursiv!
	*
	* @return array
	**/
	public function toArray() {
		$array = (array) $this;
		
		// Den @attributes entfernen
		unset($array['@attributes']);

		foreach(array_slice($array,0) as $key=>$value) {		
			if($value instanceof self && !is_null($value))
				$array[$key] = $value->toArray();
		}
		
		// Ungewollte verschachtelte Arrays vermeiden.
		if(count($array) == 1 && is_array(current($array)))
			$array = current($array);
		
		return $array;
	}
}
?>