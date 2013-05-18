<?php
/**
* Eine Sammlung an Formatierungsfunktionen.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/marhei/CoreCMS
* @date 2012-10-24
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package McF Framework
*
**/
namespace Core;

class Format {
	/**
	* Formatiert Strings so, dass sie in einer URL verwendet werden können.
	*
	* @param string $string
	* @return string
	**/
	public static function url($string) {
		return urlencode($string);
	}

	/**
	* Formatiert ein Datum
	*	
	* @param int $time
	* @param bool $passed
	* @return string
	*/
	public static function date($time, $withSecs = false, $passed = true, $long = false) {
		// Gestriges Datum herausfinden
		$date = explode('.', date('Y.m.d', time()-86400));
		// Gestriges Timestamp berechnen
		$yesterdayTime = mktime(0,0,0,$date[1], $date[2], $date[0]);

		// Morgiges Datum herausfinden
		$date = explode('.', date('Y.m.d', time()+86400));
		// Morgiger Timestamp berechnen
		$tomorrowTime = mktime(0,0,0,$date[1], $date[2], $date[0]);

		$dateString = date('d.m.'.($long ? 'Y' : ''), $time);
		if($withSecs) $timeString = date('H:i:s', $time);
		else $timeString = date('H:i', $time);

		// Unbekannt?
		if($time == 0) return 'unbekannt';

		// Schon vorbei?
		if($time < time() && $passed) return 'Zeit schon abgelaufen';

		// Heute?
		if($time < $tomorrowTime && $yesterdayTime + 86400 < $time) return 'heute, '.($long?'um ':'').$timeString.' Uhr';

		// Morgen?
		if($time < $tomorrowTime + 86400 && $time > time()) return 'morgen, '.($long?'um ':'').$timeString.' Uhr';

		return 'am '.$dateString.', '.($long?'um ':'').$timeString.' Uhr';
	}

	/**
 	* Formatiert einen Nummer
 	*
 	* @param int,float $number
 	* @param int $decimals
 	* @return string
 	**/
 	public static function number($number,$decimals=0) {
 		$pre = $number > 0 && round($number, 2) == 0 ? '>' : '';

		return $pre.number_format($number, $decimals, ',', '.');
	}
	
	/**
	* Formiert einen String
	*
	* @param String $string
	* @param bool $nl2br - Neue Zeilen in Zeilenumbrüche umwandeln.
	* @param bool $codes - Sollen BB-Codes umgewandelt werden?
	* @param int $maxChars - Wie viele Zeichen maximal?
	* @return String
	**/
	public static function string($string, $nl2br=false, $codes = false, $maxChars = 0) {
		if($maxChars && strlen($string) > $maxChars) {
			// String teilen
			$firstString = substr($string, 0, floor($maxChars / 2));
			$secondString =  substr($string, strlen($string) - floor($maxChars / 2), strlen($string));
			
			// String zusammenfügen
			$string = $firstString.'…'.$secondString;
		}
	
		// Sonderzeichen bitte nicht maskieren
		$string = htmlspecialchars($string, ENT_COMPAT, 'UTF-8', false); 
		
		// Diverse BB-Codes umwandeln
		if($codes) {
			$string = preg_replace('/\[b\](.*?)\[\/b\]/is', '<strong>\1</strong>', $string);
			$string = preg_replace('/\[i\](.*?)\[\/i\]/is', '<em>\1</em>', $string);
		}
		
		// Zeilenumbrüche einfügen
		if($nl2br) $string = nl2br($string);
		
		return  $string;
	}
	
	/**
	* Formatiert einen Prozent-Wert
	*
	* @param float $floatValue - Der Wert
	* @return string
	**/
	public static function percent($floatValue) {
		return self::unit(self::number($floatValue*100),'%');
	}
	
	/**
	* Formatiert eine Datei-Größe
	*
	* @param int $fileSize - Die Datei-Größe
	* @return string - Formatierter String
	**/
	public static function size($fileSize) {
		// Negative Zahl?
		if($fileSize < 0) {
			// Positiv machen
			$fileSize *= -1;
			// Multiplikator festlegen
			$multi = -1;
		} else // Multiplikator ist 1
			$multi = 1;
	
		foreach(array('B','KiB','MiB','GiB','TiB','PiB') as $key=>$currentSize) {
			// Größer als diese Größe?
			if($fileSize >= pow(2,10*($key+1))) continue;
			
			$fileSize /= pow(2,10*$key);
			return self::unit(self::number($fileSize*$multi,2),$currentSize);
		} 
	}
	
	/**
	* Formatiert eine Zahl mit Einheit.
	*
	* @param string $value - Zahl
	* @param string $unit - Einheit
	* @return string - Formatierter String
	**/
	public static function unit($value, $unit) {
		return $value.'&thinsp;'.$unit;
	}
}
?>