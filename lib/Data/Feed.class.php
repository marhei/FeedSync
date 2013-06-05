<?php
/**
* Eine RSS-Feed, der ein Favicon, ein Title, eine URL, eine Seiten-URL und das letzte Update beinhaltet.
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/marhei/FeedSync
* @date 2013-05-08
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Data;

class Feed implements \Core\JSON\Serializable, \Core\XML\Serializable, \Core\Manager\Indentable {
	private $id, $faviconID, $title, $request, $siteURL, $lastUpdate;
	private $paused = false, $readability = false;
	
	/**
	* Gibt die Rückgabewerte für die API zurück.
	*
	* @return array
	**/
	public function jsonSerialize() {
		return array(	'id'					=> $this->id,
						'favicon_id'			=> $this->faviconID,
						'title'					=> $this->title,
						'url'					=> $this->request->getURL(),
						'site_url'				=> $this->siteURL,
						'is_spark'				=> false,
						'last_updated_on_time'	=> $this->lastUpdate);
	}
	
	/**
	* Gibt die selben Rückgabewerte wie für den JSON-Wert.
	*
	* @return array
	**/
	public function xmlSerialize() {
		return $this->jsonSerialize();
	}
	
	/**
	* Erstellt einen neuen Feed.
	*
	* @param \Core\Header\Request $request - URL des Feeds
	**/
	public function __construct(\Core\Header\Request $request) {
		// Die URL des Feeds speichern
		$this->request = $request;
		
		// Feeddaten laden
		$this->updateFeedInformation();
		// Neue Einträge laden
		// $this->updateItemList(); // Geht erst nach dem Hinzufügen
		
		// Favicon laden
		$favicon = new Favicon($this->siteURL);
		// Favicon dem Manager hinzufügen
		Favicon\Manager::main()->addObject($favicon);
		// Favicon-ID dem Feed hinzufügen
		$this->faviconID = $favicon->getID();
	}
	
	/**
	* Gibt den Feed als Objekt zurück.
	*
	* @return XMLElement
	**/
	private function getFeedObject() {
		// Den Feed öffnen
		return \Core\XML\Element::loadURL($this->request)->channel[0];
	}
	
	/**
	* Updatet die Feed-Daten aus der URL.
	**/
	private function updateFeedInformation() {
		// Den Feed öffnen
		$feed = $this->getFeedObject();
		
		// Daten auslesen
		$this->title = (string) $feed->title;
		$this->siteURL = (string) $feed->link;
	}
	
	/**
	* Sucht nach neuen Items.
	**/
	public function updateItemList() {
		// Feed pausiert? Abbrechen!
		if($this->isPaused()) return;
	
		// Den Feed öffnen
		$feed = $this->getFeedObject();
		// Manager auslesen
		$manager = Item\Manager::main();
		// Letztes Update
		$lastUpdate = $this->lastUpdate;
	
		// Allem Items im RSS-Dings aulesen
		foreach($feed->item as $currentItem) {
			try {
				// Daten auslesen
				$title = (string) $currentItem->title;
				$html = (string) $currentItem->description;
				$author = (string) $currentItem->author;
				$url = (string) $currentItem->link;
				$createTime = strtotime((string) $currentItem->pubDate);
			
				// Der Artikel wurde vor dem letzten Aktuallisieren eingefügt? Abbruch!
				if($createTime <= $this->lastUpdate) continue;
			
				// Item erstellen
				$item = new Item($this, $title, $author, $html, $url, $createTime);
				// Item dem Manager hinzufügen
				$manager->addObject($item);
			
				// Letzte Update eintragen
				if($createTime > $lastUpdate) $lastUpdate = $createTime;
			} catch(\Exception $exception) {
				// Fehler im RSS-Eintrag? Keine weitere Reaktion!
			}
		}
		
		// Letztes Update auch in der Klasse eintragen
		$this->lastUpdate = $lastUpdate;
	}
	
	/**
	* Gibt die ID des Feeds zurück.
	*
	* @return int
	**/
	public function getID() {
		return $this->id;
	}
	
	/**
	* Setzt die ID des Feeds.
	*
	* @param int $id
	**/
	public function setID($id) {
		$this->id = $id;
	}
	
	/**
	* Gibt das Favicon zurück.
	*
	* @return Favicon
	**/
	public function getFavicon() {
		// Manager laden
		$faviconManager = Favicon\Manager::main();
		// Alle Favicons laden
		$faviconManager->loadAll();
	
		// Vom Manager laden
		return $faviconManager->getObjectForID($this->faviconID);
	}
	
	/**
	* Gibt den Titel des Feeds zurück.
	*
	* @return string
	**/
	public function getTitle() {
		return $this->title;
	}
	
	/**
	* Gibt die URL des Feeds zurück.
	*
	* @return string
	**/
	public function getRequest() {
		return $this->request;
	}
	
	/**
	* Gibt die Seiten-URL des Feeds zurück.
	*
	* @return string
	**/
	public function getSiteURL() {
		return $this->siteURL;
	}
	
	/**
	* Gibt das letze Update zurück.
	*
	* @return int
	**/
	public function getLastUpdate() {
		return $this->lastUpdate;
	}
	
	/**
	* Gibt die Anzahl der Items in diesem Feed zurück.
	*
	* @return int
	**/
	public function countAllItems() {
		return Item\Manager::main()->countAllInFeed($this->id);
	}
	
	/**
	* Gibt die Anzahl der ungelesenen Items in diesem Feed zurück.
	*
	* @return int
	**/
	public function countUnreadItems() {
		return Item\Manager::main()->countUnreadInFeed($this->id);
	}
	
	/**
	* Markiert alle Beträge des Feeds als gelesen.
	*
	* @param int $before - Bevor eine bestimmten Zeit [optional]
	**/
	public function markAsRead($before = false) {
		// Manager
		$manager = Item\Manager::main();
		// Items laden
		$manager->loadUnreadInFeed($this->id);
		// Array mit IDs die als gelesen markiert werden sollen
		$ids = array();
		
		// Manager durchlaufen
		foreach($manager as $currentItem) {
			// Item ist bereits als gelesen markiert oder nach dem $before gekommen
			if($currentItem->getAction()->isRead() || ($currentItem->getCreateTime() > $before && $before)) continue;
			
			// ID dem Array hinzufügen
			$ids[] = $currentItem->getID();
		}
		
		// Array durchlaufen und als gelesen markieren
		foreach($ids as $current) $manager->markItemAs($current, 'read');
	}
	
	/**
	* Markiert den Feed als pausiert.
	**/
	public function pause() {
		$this->paused = true;
	}
	
	/**
	* Markiert den Feed als unpausiert.
	**/
	public function unpause() {
		$this->paused = false;
	}
	
	/**
	* Gibt zurück, ob der Feed pausiert ist.
	*
	* @return bool
	**/
	public function isPaused() {
		return $this->paused;
	}
	
	/**
	* Setzt, dass Readability genutzt werden soll.
	**/
	public function useReadability() {
		$this->readability = true;
	}
	
	/**
	* Setzt, dass Readability nicht genutzt werden soll.
	**/
	public function unuseReadability() {
		$this->readability = false;
	}
	
	/**
	* Gibt zurück, ob die Feed-Einträge via Readability voll angezeigt werden sollen.
	*
	* @return bool
	**/
	public function isReadability() {
		return $this->readability;
	}
}
?>