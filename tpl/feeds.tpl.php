
<p>
    Willkommen auf dem FeedSync-Backend. Da FeedSync lediglich ein Syncdienst mit API ist, hast du hier nicht die Möglichkeit, den Inhalt deiner Feeds zu beobachten.
    Du kannst hier neue Feeds hinzufügen oder alte löschen. Weitere Funktionen, zum Beispiel zum Sortieren von Feeds in Gruppen, kommen in spätere Versionen.
</p>

<? if(isset(self::$moduleVars['error'])): ?>
    <div class="alert alert-error">
    	<strong>Verdammt!</strong>
    	<?= \Core\Format::string(self::$moduleVars['error']) ?>
    </div>
<? endif; ?>

<table class="table table-striped">
    <thead>
    	<tr>
    		<th>#</th>
    		<th>Titel</th>
    		<th>Feed</th>
    		<th>Aktuallisierung</th>
    		<th>Items</th>
    		<th>Aktionen</th>
    	</tr>
    </thead>
    <tbody>
    	<? foreach(self::$moduleVars['manager'] as $currentElement): ?>
    		<tr>
    			<td><?= \Core\Format::number($currentElement->getID()) ?></td>
    			<td>
    				<a href="<?= \Core\Format::string($currentElement->getSiteURL()) ?>" title="Webseite besuchen">
    					<img src="data:<?= $currentElement->getFavicon()->getData() ?>" alt="Favicon">
						<?= \Core\Format::string($currentElement->getTitle()) ?>
    				</a>
    			</td>
    			<td><?= \Core\Format::string($currentElement->getURL()) ?></td>
    			<td><?= \Core\Format::date($currentElement->getLastUpdate(), false, false) ?></td>
    			<td>
    				<span class="badge" title="davon <?= \Core\Format::number($currentElement->countUnreadItems()) ?> ungelesen">
    					<?= \Core\Format::number($currentElement->countAllItems()) ?>
    				</span>
    			</td>
    			<td>
    				<div class="btn-group">
    					<a class="btn btn-mini btn-danger" href="index.php?deleteFeed=true&feedID=<?= $currentElement->getID() ?>">
    						<i class="icon-trash icon-white"></i>
    					</a>
    					<button class="btn btn-mini btn-danger dropdown-toggle" data-toggle="dropdown">
    						<span class="caret"></span>
    					</button>
    					<ul class="dropdown-menu pull-right">
    						<li><a href="index.php?deleteFeedItems=true&feedID=<?= $currentElement->getID() ?>">Nur Items</a></li>
    						<li><a href="index.php?deleteReadFeedItems=true&feedID=<?= $currentElement->getID() ?>">Nur gelesene Items</a></li>
    					</ul>
    				</div>
    			</td>
    		</tr>
    	<? endforeach; ?>
    	<? if(!count(self::$moduleVars['manager'])): ?>
    		<tr>
    			<td colspan="6" class="text-center">
    				Es wurde noch keine RSS-Feeds hinzugefügt.
    			</td>
    		</tr>
    	<? endif; ?>
    </tbody>
</table>

<form class="form-inline text-center" action="index.php?addFeed=true" method="post">
     <div class="input-prepend">
     	<span class="add-on">http://</span>
     	<input type="text" placeholder="Feed-URL" name="feedURL">
     </div>
     <div class="btn-group">
    	<input class="btn" type="submit" value="Feed hinzufügen">
    	<button class="btn dropdown-toggle" data-toggle="dropdown">
    		<span class="caret"></span>
    	</button>
    	<ul class="dropdown-menu pull-right">
    		<li><a href="#uploadModal" data-toggle="modal">OPML-Datei importieren…</a></li>
    	</ul>
     </div>
</form>

<form method="post" action="index.php?importOPML=true" enctype="multipart/form-data">
<div id="uploadModal" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">OPML-Datei importieren…</h3>
	</div>
		
	<div class="modal-body">
	    <p>
	    	Wähle eine OPML-Datei aus, damit die enthaltenen Feeds automatisch importiert werden können.
	    	Feeds die bereits in FeedSync vorhanden sind werden kein zweites Mal erstellt.
	    </p>
	    <p>
	    	<input type="file" name="opmlFile">
	    </p>
	    <p>
	    	Das Einfügen der Daten aus der OPML-Datei kann einen Moment dauern.
	    </p>
	</div>
	
	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">Abbrechen</button>
	    <button class="btn btn-primary">Importieren</button>
	</div>
	</div>
</form>