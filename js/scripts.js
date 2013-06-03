/**
* Zusätzliches Javascript
*
* @copyright Copyright 2013 Marcel Heisinger
* @link https://github.com/marhei/FeedSync
* @date 2013-05-27
* @license Apache License v2 (http://www.apache.org/licenses/LICENSE-2.0.txt)
* @author Marcel Heisinger
* @package FeedSync
**/

// Tooltips aktivieren
$(function () {
	$("[rel='tooltip']").tooltip({
		container: 'body'
	});
});

// Funktion um den Namen eine Gruppe zu ändern
function changeGroupName(spanElement) {
	// Den aktuellen Gruppenamen auslesen
	var currentName = $(spanElement).text();
	
	// Das onClick-Event nehmen
	$(spanElement).removeAttr('onclick');
	
	// Neues Feld setzen
	$(spanElement).html('<form action="javascript:saveGroupName()"><input onblur="saveGroupName()" id="groupNameInput" value="'+currentName+'"></form>');
	// Fokosieren
	$('#groupNameInput').focus();
}

// Funktion um den Namen einer Gruppe zu speichern
function saveGroupName() {
	// Neuer Inhalt
	var newName = $('#groupNameInput').val();
	// Encoderter Name
	var encodedNewName = encodeURIComponent(newName);
	
	// Überelement ermitteln
	var spanElement = $('#groupNameInput').parent().parent();
	// Gruppen-ID ermitteln
	var groupID = $(spanElement).attr('data-group-id');
	
	// Das onClick-Event hinzufügen
	$(spanElement).attr('onclick', 'changeGroupName(this)');
	// Neuer Name setzen
	$(spanElement).text(newName);
	
	// Ajax anfrage senden
	ajaxRequest(saveGroupNameURL+'&groupID='+groupID+'&newName='+encodedNewName);
}

// Neuladen der Feeds
function refreshFeeds() {	
	// Ajax-Request anfangen
	ajaxRequest(refreshURL, function() {
		reloadPage();
	});
}

// Alle Feeds als gelesen markieren
function markFeedsAsRead() {
	ajaxRequest(markFeedsAsReadURL);
}

// Checkt in den Inhalt des Feed-URL-Feelds
function parseFeedURL() {
	// Inhalt des Feed-URL-Felds
	var feedURL = $('#feedURL').val();
	
	// HTTPS?
	if(feedURL.indexOf('https:') == 0) {
		// Add-on ändern
		$('#feedURLAddon').text('https://');
		// Inputfeld setzen
		$('#httpsInput').val(1);
		
		// Protokoll entfernen
		var feedURL = feedURL.slice(8);
	} else if(feedURL.indexOf('http:') == 0) { // HTTP
		// Add-on ändern
		$('#feedURLAddon').text('http://');
		// Inputfeld setzen
		$('#httpsInput').val(0);
		
		// Protokoll entfernen
		var feedURL = feedURL.slice(7);
	}
	
	// Neue URL setzen
	$('#feedURL').val(feedURL);
}

// Ajax-Request mit Progressbar
function ajaxRequest(url) {
	// Callback
	if(arguments.length > 1) callback = arguments[1];
	else callback = function() {};

	// Progressbar anzeigen
	$('#progress-bar').css('display', 'block');
	
	// Ajaxrequest senden
	$.ajax(url).done(function() {
		// Progressbar ausblenden
		$('#progress-bar').css('display', 'none');
		
		// Eigene Callbackmethode
		callback();
	});
}

// Seite neuladen
function reloadPage() {
	// Seite neuladen
	window.location.href = reloadURL;
}