<div class="container">
	<div class="page-header">
		<h1>FeedSync <small>Syncdienst für RSS-Clients</small></h1>
	</div>
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
				<th>Link</th>
				<th>Items</th>
				<th>Aktionen</th>
			</tr>
		</thead>
		<tbody>
			<? foreach(self::$moduleVars['manager'] as $currentElement): ?>
				<tr>
					<td><?= \Core\Format::number($currentElement->getID()) ?></td>
					<td>
						<img src="data:<?= $currentElement->getFavicon()->getData() ?>" alt="Favicon">
						<?= \Core\Format::string($currentElement->getTitle()) ?>
					</td>
					<td><?= \Core\Format::string($currentElement->getURL()) ?></td>
					<td><?= \Core\Format::string($currentElement->getSiteURL()) ?></td>
					<td><span class="badge">?</span></td>
					<td>
						<div class="btn-group">
							<button class="btn btn-mini btn-danger" onclick="location.href='index.php?deleteFeed=true&feedID=<?= $currentElement->getID() ?>'">Löschen</button>
							<button class="btn btn-mini btn-danger dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								<li><a href="#">Items löschen</a></li>
								<li><a href="#">gelesene löschen</a></li>
							</ul>
  						</div>
					</td>
				</tr>
			<? endforeach; ?>
		</tbody>
	</table>
	
	<form class="form-inline text-center" action="index.php?addFeed=true" method="post">
		 <div class="input-prepend">
		 	<span class="add-on">http://</span>
		 	<input type="text" placeholder="Feed-URL" name="feedURL">
		 </div>
		 <input class="btn" type="submit" value="Feed hinzufügen">
	</form>
</div>