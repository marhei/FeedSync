<?php
/**
* Interface, das markiert, dass ein Objekt in das XML-Format umgewandelt werden kann
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/marhei/FeedSync
* @date 2013-05-20
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Core\XML;

interface Serializable {
	/**
	* Gibt die Rückgabewerte zum Serialisieren zurück.
	*
	* @return array
	**/
	public function xmlSerialize();
}
?>