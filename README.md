FeedSync
========

PHP-Programm, das ohne Webinterface zum Syncen von Fever-kompatiblen RSS-Readern verwendet werden kann. Eine kostenlose Möglichkeit, für die Leute, die lediglich einen Sync-Dienst suchen und keine Weboberfläche benötigen.

**Derzeit befindet sich FeedSync noch in Beta-Phase, dadurch kann es noch zu Fehlern kommen. Ich bitte euch, diese Fehler zu melden.**

### Systemvoraussetzungen

- PHP 5.3 mit installierter JSON-Erweiterung
- mindestens 32 MiB Arbeitsspeicher für PHP
- MySQL in einer aktuellen Version

### Kompatibilität
Das Skript ist mit allein Apps, die „Fever“ als Syncmöglichkeit anbieten kompatibel. Zum Beispiel: Reeder

### Einschränkungen
Es gibt einige Funktionen von Fever die durch FeedSync bewusst nicht unterstützt werden. Dazu gehört vor allem Hot Links, aber auch die „Sparks“- und die „Kindling“-Supergruppe.

Wir möchten Fever nicht nachbauen, wir verwenden lediglich eine API die bereits von einigen Apps unterstützt wird.

### Installation
Derzeit gibt es für die Installation noch keine grafische Unterstützung. Die Installation ist jedoch trotzdem ziemlich einfach.

Zuerst muss die „install.sql“-Datei in eine Datenbank-Tabelle importiert werden. Danach müssen in der „config.inc.php“ die MySQL-Zugangsdaten eingetragen werden.

Zusätzlich sollte die Benutzerdaten, die auch in der „config.inc.php“ stehen, angepasst werden.