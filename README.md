FeedSync
========

PHP-Programm, das ohne Webinterface zum Syncen von Fever-kompatiblen RSS-Readern verwendet werden kann. Eine kostenlose Möglichkeit, für die Leute, die lediglich einen Sync-Dienst suchen und keine Weboberfläche benötigen.

**Derzeit befindet sich FeedSync noch in der Entwicklung und bietet noch nicht den vollen Funktionsumfang. Wir möchten bis spätestens 1. Juli einen funktionalen Ersatz zu Google Reader bieten.**

### Systemvoraussetzungen

- PHP 5.3 mit installierter JSON-Erweiterung
- mindestens 32 MiB Arbeitsspeicher für PHP
- MySQL in einer aktuellen Version

### Kompatibilität
Das Skript ist mit allein Apps, die „Fever” als Syncmöglichkeit anbieten komptibel. Zum Beispiel: Reeder

### Einschränkungen
Es gibt einige Funktionen von Fever die durch FeedSync bewusst nicht unterstützt werden. Dazu gehört vor allem Hot Links. Wir möchten Fever nicht nachbauen, wir verwenden lediglich eine API die breits von einigen Apps unterstützt wird.