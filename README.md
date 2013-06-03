FeedSync
========

PHP-Programm, das ohne RSS-Webinterface zum Syncen von Fever-kompatiblen RSS-Readern verwendet werden kann. Eine kostenlose Möglichkeit, für die Leute, die lediglich einen Sync-Dienst suchen.

Featurerequests oder Fehlermeldungen werden gerne als Issue entgegen genommen.

### Systemvoraussetzungen

- mindestens PHP 5.3
- mindestens 32 MiB Arbeitsspeicher für PHP
- MySQL in einer aktuellen Version
- modernen Browser für das Backend

### Kompatibilität
Das Skript ist mit allein Apps, die „Fever“ als Syncmöglichkeit anbieten kompatibel. Dazu gehören um Beispiel:

- Reeder (derzeit nur iPhone-Version)
- Sunstroke
- …

### Einschränkungen
Es gibt einige Funktionen von Fever die durch FeedSync bewusst nicht unterstützt werden. Dazu gehört vor allem Hot Links, aber auch die „Sparks“- und die „Kindling“-Supergruppe. Es gibt ebenso wenig ein RSS-Webinterface.

Ich möchten Fever nicht nachbauen, ich verwenden lediglich eine API die bereits von einigen Apps unterstützt wird. Zusätzlich unterscheidet sich das Konzept von FeedSync an einigen Stellen.

### Installation
Derzeit gibt es für die Installation noch keine grafische Unterstützung. Die Installation ist jedoch trotzdem ziemlich einfach:

- „install.sql“-Datei in eine Datenbank-Tabelle importieren
- „config.inc.php“ anpassen:
	- MySQL-Zugangsdaten ändern
	- Benutzerdaten anpassen 