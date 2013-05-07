<?php
/**
* Aufgaben, die mit einer Tabelle gemacht werden können.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/FeedSync/FeedSync
* @date 2012-11-30
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package McF Framework
**/
namespace Core\MySQL;

class Table {
	private $table,$mysqlInstance;
		
	/**
	* Nimmt die Tabelle und die MySQL-Instanz zurück.
	*
	* @param MySQL $mysqlInstance - Die MySQL-Instanz
	* @param string $table - Die Tabelle
	**/
	public function __construct(\Core\MySQL $mysqlInstance, $table) {
		$this->mysqlInstance = $mysqlInstance;
		$this->table = $table;
	}
	
	/**
	* Gibt die Anzahl der Elemente in dieser Tabelle zurück.
	*
	* @param string/array $where - Welche? [oprional]
	* @return int - Anzahl
	**/
	public function count($where = '') {
		$sql = 'SELECT count(*) FROM '.$this->table;
		$sql .= $this->makeWhereString($where);
		
		return $this->mysqlInstance->query($sql)->fetch()['count(*)'];
	}
	
	/**
	* Gibt den Inhalt einer Tabelle mit den Konditionen zurück
	*
	* @param string/array $where - Welche? [optional]
	* @return MySQLQuery - MySQL-Instanz
	**/
	public function select($where = '') {
		$sql = 'SELECT * FROM '.$this->table;
		$sql .= $this->makeWhereString($where);
		
		return $this->mysqlInstance->query($sql);
	}
	
	/**
	* Fügt einen neuen Eintrag in die Tabelle.
	*
	* @param array $content - Inhalt
	* @param array $update - Was soll geupdatet werden, wenn der bereits vorhanden ist. [optional]
	* @return MySQLQuery - Query-Objekt
	**/
	public function insert(array $content, array $update = []) {
		$sql = 'INSERT INTO '.$this->table;
		$sql .= $this->makeSetString($content);
		if(count($update)) {
			$sql .= 'ON DUPLICATE KEY UPDATE';
		   	$sql .= $this->makeSetString($update,false); 
		} 
		
		return $this->mysqlInstance->query($sql);
	}
	
	/**
	* Updatet einen Eintrag in der Tabelle
	*
	* @param array $content - Inhalt
	* @param string/array $where - Welches?
	* @return MySQLQuery - Query-Object
	**/
	public function update(array $content, $where) {
		$sql = 'UPDATE '.$this->table;
		$sql .= $this->makeSetString($content);
		$sql .= $this->makeWhereString($where);
		
		return $this->mysqlInstance->query($sql);
	}
	
	/**
	* Löscht einen Eintrag in der Tabelle
	*
	* @param string/array $where - Welches?
	* @return MySQLQuery - Query-Object
	**/
	public function delete($where) {
		$sql = 'DELETE FROM '.$this->table;
		$sql .= $this->makeWhereString($where);

		return $this->mysqlInstance->query($sql);
	}
	
	
	/**
	* Macht einen SQL-Set-String aus einem Array
	*
	* @param array $content - Inhalt
	* @param bool $setKeyword - Soll das Set-Keyword verwendet werden? [optional]
	* @return string - Der SQL-String
	**/
	protected function makeSetString(array $content, $setKeyword = true) {
		$setString = '';
		foreach($content as $colName => $value) {
			if (!empty($setString)) $setString .= ', ';
			$setString .= $colName.'='.$this->mysqlInstance->quoteString($value);
		}
		
		return ($setKeyword?' SET ':' ').$setString.' ';
	}
	
	/**
	* Macht einen SQL-Set-String aus einem Array
	*
	* @param string/array $content - Inhalt
	* @return string - Der SQL-String
	**/
	protected function makeWhereString($content) {
		if(is_array($content)) {
			$setString = '';
			foreach($content as $colName => $value) {
				if (!empty($setString)) $setString .= ' AND ';
				
				// Ein Array? Dann Oder-Verknüpfung
				if(is_array($value)) {
					$setString .= '( ';
					$start = true;
					foreach($value as $colName => $value) {
						if (!$start) $setString .= ' OR ';
						
						$setString .= $colName.'='.$this->mysqlInstance->quoteString($value);
						
						$start = false;
					}
					$setString .= ') ';
				} else
					$setString .= $colName.'='.$this->mysqlInstance->quoteString($value);
			}
		
			return ' WHERE '.$setString.' ';
		} else if(!empty($content))
			return ' WHERE '.$content.' ';
	   
	}
}
?>