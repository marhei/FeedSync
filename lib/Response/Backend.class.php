<?php
/**
* Stellt ein Mini-Backend für die Funktionen, die über die API nicht möglich sind, zur Verfügung
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/marhei/FeedSync
* @date 2013-05-08
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/
namespace Response;

class Backend {
	const HEADER_TEMPLATE = 'header';
	const FOOTER_TEMPLATE = 'footer';
	
	const DEFAULT_MODULE = 'Feeds';
	const MODULE_NAMESPACE = '\Response\Backend\\';
	
	private static $moduleVars = array();
	private static $backendModules = array('Feeds', 'Groups');
	
	/**
	* Eine Module-Variable setzen.
	*
	* @param string $key
	* @param mixed $value
	**/
	public static function setModuleVar($key, $value) {
		// Varibale setzen
		self::$moduleVars[$key] = $value;
	}
	
	/**
	* Gibt den Namen des Moduls zurück.
	*
	* @return string
	**/
	public static function getModuleName() {
		return \Core\Request::GET('module', self::DEFAULT_MODULE);
	}
	
	/**
	* Macht einen Modul-Link.
	*
	* @param array $options - Optionen [optional]
	* @param string $module - Welches Module? [optional]
	* @return string
	**/
	public static function createModuleLink(array $options = array(), $module = NULL) {
		// Module ermitteln
		$module = $module ?: self::getModuleName();
		// Der Modul-Name die URL einfügen
		$url = 'index.php?module='.$module;
		
		// Optionen anhängen
		foreach($options as $key => $currentOption)
			$url .= '&'.$key.'='.urlencode($currentOption);
		
		return $url;
	}
	// Alias
	public static function cml(array $options = array(), $module = NULL) {
		return self::createModuleLink($options, $module);
	}

	/**
	* Fügt eine HTTP-Authentifizierung hinzu.
	**/
	public function __construct() {
		// Header-Instanz laden
		$header = \Core\Header::main();
		
		// Eine Authentifzierung hinzufügen
		$header->addAuthentication(	\Core\Language::main()->get('auth', 'realm'),
									array(\Config\User\MAIL),
									array(\Config\User\PASSWORD));
									
		// Das angeforderte Module öffnen
		$this->openRequestedModule();
	}
	
	/**
	* Öffnet das angeforderte Module.
	**/
	private function openRequestedModule() {
		// Welches Modul wurde angefordert?
		$module = self::getModuleName();
		// Einen Klassennamen daraus bauen
		$moduleClass = self::MODULE_NAMESPACE.$module;
		
		// Existiert das Module?
		if(!class_exists($moduleClass)) throw new \Exception('Das angeforderte Module existiert nicht.', 2);
		// Modul öffnen
		new $moduleClass();
		
		// Template-Module gesetzt?
		if(isset(self::$moduleVars['template'])) {
			// Header-Template einfügen
			$this->openTemplate(self::HEADER_TEMPLATE);
			// Module-Template aufrufen
			$this->openTemplate(self::$moduleVars['template']);
			// Header-Template einfügen
			$this->openTemplate(self::FOOTER_TEMPLATE);
		}
	}
	
	/**
	* Gibt zurück, ob ein Template existiert.
	*
	* @param string $template
	* @return bool
	**/
	private function existTemplate($template) {
		// Name der Template-Datei basteln
		$templateFile = TEMPLATE_PATH.$template.'.tpl.php';	
		// Existiert die Template-Datei?
		return file_exists($templateFile);
	}
	
	/**
	* Öffnet ein Template und stellt es dem User da.
	*
	* @param string $template - Datei im Template-Ordner
	**/
	private function openTemplate($template) {
		// Name der Template-Datei basteln
		$templateFile = TEMPLATE_PATH.$template.'.tpl.php';	
	
		// Existiert das Template?
		if(!file_exists($templateFile)) throw new \Exception('Das angeforderte Template existiert nicht.', 1);
		// Das Template öffnen
		include $templateFile;
	}
}
?>