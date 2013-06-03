<table class="table table-striped">
    <thead>
    	<tr>
    		<th><?= \Core\Language::main()->get('feeds', 'tableID') ?></th>
    		<th><?= \Core\Language::main()->get('feeds', 'tableTitle') ?></th>
    		<th><?= \Core\Language::main()->get('feeds', 'tableFeed') ?></th>
    		<th><?= \Core\Language::main()->get('feeds', 'tableRefresh') ?></th>
    		<th><?= \Core\Language::main()->get('feeds', 'tableItems') ?></th>
    		<th><?= \Core\Language::main()->get('feeds', 'tableOptions') ?></th>
    		<th><?= \Core\Language::main()->get('feeds', 'tableActions') ?></th>
    	</tr>
    </thead>
    <tbody>
    	<? foreach(self::$moduleVars['manager'] as $currentElement): ?>
    		<tr>
    			<td><?= \Core\Format::number($currentElement->getID()) ?></td>
    			<td>
    				<a href="<?= \Core\Format::string($currentElement->getSiteURL()) ?>" title="<?= \Core\Language::main()->get('feeds', 'visitWebsite') ?>">
    					<img src="data:<?= $currentElement->getFavicon()->getData() ?>" alt="<?= \Core\Language::main()->get('feeds', 'favicon') ?>" width="16" height="16">
						<?= \Core\Format::string($currentElement->getTitle()) ?>
    				</a>
    			</td>
    			<td><?= \Core\Format::string($currentElement->getRequest()->getURL()) ?></td>
    			<td><?= \Core\Format::date($currentElement->getLastUpdate(), false) ?></td>
    			<td><?= \Core\Format::number($currentElement->countAllItems()) ?></td>
    			<td>
    				<div class="btn-group">
    					<a class="btn btn-mini" rel="tooltip" href="#" title="<?= \Core\Language::main()->get('feeds', 'readabilityTitle') ?>">
    						<i class="icon-plus-sign"></i>
    					</a>
    					<a class="btn btn-mini <? if($currentElement->isPaused()): ?>active<? endif; ?>"
    						rel="tooltip"title="<?= \Core\Language::main()->get('feeds', 'pauseTitle') ?>"
    						href="<?= self::cml(array('pauseFeed'=>true, 'feedID'=>$currentElement->getID(), 'pause'=>!$currentElement->isPaused())) ?>">
    						<i class="icon-pause"></i>
    					</a>
    				</div>
    			</td>
    			<td>
    				<div class="btn-group">
    					<a class="btn btn-mini btn-danger" href="<?= self::cml(array('deleteFeed'=>true, 'feedID'=>$currentElement->getID())) ?>">
    					    <i class="icon-trash icon-white"></i>
    					</a>
    					<button class="btn btn-mini btn-danger dropdown-toggle" data-toggle="dropdown">
    					    <span class="caret"></span>
    					</button>
    					<ul class="dropdown-menu pull-right">
    					    <li>
    					    	<a href="<?= self::cml(array('deleteFeedItems'=>true, 'feedID'=>$currentElement->getID())) ?>">
    					    		<?= \Core\Language::main()->get('feeds', 'deleteOnlyItems') ?>
						    	</a>
						    </li>
    					    <li>
    					    	<a href="<?= self::cml(array('deleteReadFeedItems'=>true, 'feedID'=>$currentElement->getID())) ?>">
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
    			<td colspan="7" class="text-center">
    				<?= \Core\Language::main()->get('feeds', 'noFeeds') ?>
    			</td>
    		</tr>
    	<? endif; ?>
    </tbody>
</table>

<form class="form-inline text-center" action="<?= self::cml(array('addFeed'=>true)) ?>" method="post" onsubmit="parseFeedURL()">
     <div class="input-prepend">
     	<span class="add-on" id="feedURLAddon">http://</span>
     	<input type="text" placeholder="<?= \Core\Language::main()->get('feeds', 'feedURL') ?>" name="feedURL" id="feedURL" onchange="parseFeedURL()">
     </div>
	 <input type="hidden" name="https" value="0" id="httpsInput">
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

<form method="post" action="<?= self::cml(array('importOPML'=>true)) ?>" enctype="multipart/form-data">
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
	    <button class="btn btn-primary" id="opmlImportButton"><?= \Core\Language::main()->get('feeds', 'import') ?></button>
	</div>
	</div>
</form>