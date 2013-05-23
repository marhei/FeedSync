<p><?= \Core\Language::main()->get('feeds', 'intro') ?></p>

<? if(isset(self::$moduleVars['error'])): ?>
    <div class="alert alert-error">
    	<strong><?= \Core\Language::main()->get('feeds', 'errorIntro') ?></strong>
    	<?= \Core\Format::string(self::$moduleVars['error']) ?>
    </div>
<? endif; ?>

<table class="table table-striped">
    <thead>
    	<tr>
    		<th><?= \Core\Language::main()->get('feeds', 'tableID') ?></th>
    		<th><?= \Core\Language::main()->get('feeds', 'tableTitle') ?></th>
    		<th><?= \Core\Language::main()->get('feeds', 'tableFeed') ?></th>
    		<th><?= \Core\Language::main()->get('feeds', 'tableRefresh') ?></th>
    		<th><?= \Core\Language::main()->get('feeds', 'tableItems') ?></th>
    		<th><?= \Core\Language::main()->get('feeds', 'tableActions') ?></th>
    	</tr>
    </thead>
    <tbody>
    	<? foreach(self::$moduleVars['manager'] as $currentElement): ?>
    		<tr>
    			<td><?= \Core\Format::number($currentElement->getID()) ?></td>
    			<td>
    				<a href="<?= \Core\Format::string($currentElement->getSiteURL()) ?>" title="<?= \Core\Language::main()->get('feeds', 'visitWebsite') ?>">
    					<img src="data:<?= $currentElement->getFavicon()->getData() ?>" alt="<?= \Core\Language::main()->get('feeds', 'favicon') ?>">
						<?= \Core\Format::string($currentElement->getTitle()) ?>
    				</a>
    			</td>
    			<td><?= \Core\Format::string($currentElement->getURL()) ?></td>
    			<td><?= \Core\Format::date($currentElement->getLastUpdate(), false) ?></td>
    			<td><?= \Core\Format::number($currentElement->countAllItems()) ?></td>
    			<td>
    				<div class="btn-group">
    					<a class="btn btn-mini btn-danger" href="index.php?deleteFeed=true&feedID=<?= $currentElement->getID() ?>">
    						<i class="icon-trash icon-white"></i>
    					</a>
    					<button class="btn btn-mini btn-danger dropdown-toggle" data-toggle="dropdown">
    						<span class="caret"></span>
    					</button>
    					<ul class="dropdown-menu pull-right">
    						<li>
    							<a href="index.php?deleteFeedItems=true&feedID=<?= $currentElement->getID() ?>">
    								<?= \Core\Language::main()->get('feeds', 'deleteOnlyItems') ?>
								</a>
							</li>
    						<li>
    							<a href="index.php?deleteReadFeedItems=true&feedID=<?= $currentElement->getID() ?>">
									<?= \Core\Language::main()->get('feeds', 'deleteOnlyReadItems') ?>
    							</a>
    						</li>
    					</ul>
    				</div>
    			</td>
    		</tr>
    	<? endforeach; ?>
    	<? if(!count(self::$moduleVars['manager'])): ?>
    		<tr>
    			<td colspan="6" class="text-center">
    				<?= \Core\Language::main()->get('feeds', 'noFeeds') ?>
    			</td>
    		</tr>
    	<? endif; ?>
    </tbody>
</table>

<form class="form-inline text-center" action="index.php?addFeed=true" method="post">
     <div class="input-prepend">
     	<span class="add-on">http://</span>
     	<input type="text" placeholder="<?= \Core\Language::main()->get('feeds', 'addFeed') ?>" name="feedURL">
     </div>
     <div class="btn-group">
    	<input class="btn" type="submit" value="<?= \Core\Language::main()->get('feeds', 'addFeed') ?>">
    	<button class="btn dropdown-toggle" data-toggle="dropdown">
    		<span class="caret"></span>
    	</button>
    	<ul class="dropdown-menu pull-right">
    		<li><a href="#uploadModal" data-toggle="modal"><?= \Core\Language::main()->get('feeds', 'importOPML') ?></a></li>
    	</ul>
     </div>
</form>

<form method="post" action="index.php?importOPML=true" enctype="multipart/form-data">
<div id="uploadModal" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel"><?= \Core\Language::main()->get('feeds', 'importOPML') ?></h3>
	</div>
		
	<div class="modal-body">
	    <p><?= \Core\Language::main()->get('feeds', 'introOPML') ?></p>
	    <p>
	    	<input type="file" name="opmlFile">
	    </p>
	    <p><?= \Core\Language::main()->get('feeds', 'outroOPML') ?></p>
	</div>
	
	<div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true"><?= \Core\Language::main()->get('feeds', 'cancel') ?></button>
	    <button class="btn btn-primary"><?= \Core\Language::main()->get('feeds', 'import') ?></button>
	</div>
	</div>
</form>