<div class="container">
	<div class="page-header">
		<h1>FeedSync <small>Syncdienst für RSS-Clients</small></h1>
	</div>
	<p>
		Willkommen auf dem FeedSync-Backend. Da FeedSync lediglich ein Syncdienst mit API ist, hast du hier nicht die Möglichkeit, den Inhalt deiner Feeds zu beobachten.
		Du kannst hier neue Feeds hinzufügen oder alte löschen. Weitere Funktionen, zum Beispiel zum Sortieren von Feeds in Gruppen, kommen in spätere Versionen.
	</p>
	
	<table class="table table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th>Titel</th>
				<th>Link</th>
				<th>Items</th>
				<th>Aktionen</th>
			</tr>
		</thead>
		<tbody>
			<? foreach(self::$moduleVars['manager'] as $currentElement): ?>
				<tr>
					<td><?= \Core\Format::number($currentElement->getID()) ?></td>
					<td><?= \Core\Format::string($currentElement->getTitle()) ?></td>
					<td><?= \Core\Format::string($currentElement->getSiteURL()) ?></td>
					<td><span class="badge">?</span></td>
					<td>
						<div class="btn-group">
							<button class="btn btn-mini btn-danger">Löschen</button>
							<button class="btn btn-mini btn-danger dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								<li><a href="#">Alle Items löschen</a></li>
								<li><a href="#">Gelesene löschen</a></li>
							</ul>
  						</div>
					</td>
				</tr>
			<? endforeach; ?>
		</tbody>
	</table>
</div>