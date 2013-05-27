// Funktion um den Namen eine Gruppe zu ändern
function changeGroupName(spanElement) {
	// Den aktuellen Gruppenamen auslesen
	var currentName = $(spanElement).text();
	
	// Das onClick-Event nehmen
	$(spanElement).removeAttr('onclick');
	
	// Neues Feld setzen
	$(spanElement).html('<input onblur="saveGroupName()" id="groupNameInput" value="'+currentName+'">');
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
	var spanElement = $('#groupNameInput').parent();
	// Gruppen-ID ermitteln
	var groupID = $(spanElement).attr('data-group-id');
	
	// Das onClick-Event hinzufügen
	$(spanElement).attr('onclick', 'changeGroupName(this)');
	// Neuer Name setzen
	$(spanElement).text(newName);
	
	// Ajax anfrage senden
	$.ajax(saveGroupNameURL+'&groupID='+groupID+'&newName='+encodedNewName);
}