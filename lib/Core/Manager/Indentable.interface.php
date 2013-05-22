<?php
/**
* Zeigt an, dass in dieser Klasse eine ID gesetzt werden kann.
* Der Manager setzt dann automatisch die richtige ID in der Klasse.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/marhei/FeedSync
* @date 2013-05-09
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Core\Manager;

interface Indentable {
	/**
	* Setzt die ID des Objekts automatisch.
	*
	* @param int $id
	**/
	public function setID($id);
}
?>