<table class="table table-striped">
    <thead>
    	<tr>
    		<th><?= \Core\Language::main()->get('groups', 'tableID') ?></th>
    		<th style="width: 250px;"><?= \Core\Language::main()->get('groups', 'tableName') ?></th>
    		<th>
    			<?= \Core\Language::main()->get('groups', 'tableFeeds') ?>
    			<button id="submitButton" class="btn btn-mini btn-primary pull-right" disabled="disabled" onclick="$('#changeGroupForm').submit()">
    			    <i class="icon-hdd icon-white"></i> <?= \Core\Language::main()->get('groups', 'updateFeeds') ?>
    			</button>
    		</th>
    		<th><?= \Core\Language::main()->get('feeds', 'tableActions') ?></th>
    	</tr>
    </thead>
    <tbody>
    	<? foreach(self::$moduleVars['manager'] as $currentElement): ?>
    		<tr>
    			<td><?= \Core\Format::number($currentElement->getID()) ?></td>
    			<td>
    				<span onclick="changeGroupName(this)"
    					title="Klicken um den Namen zu ändern!"
    					data-group-id="<?= $currentElement->getID() ?>"><?= \Core\Format::string($currentElement->getTitle()) ?></span>
    			</td>
    			<td>
        			<select multiple="multiple" form="changeGroupForm" style="width: 400px;" onchange="$('#submitButton').removeAttr('disabled')" name="groupRelationships[<?= $currentElement->getID() ?>][]">
        				<? foreach(self::$moduleVars['feedManager'] as $currentFeed): ?>
        					<option value="<?= $currentFeed->getID() ?>"
        						<? if($currentElement->getRelationship()->existFeed($currentFeed->getID())): ?> selected="selected"<? endif; ?> >
        						<?= \Core\Format::number($currentFeed->getID()) ?>:
        						<?= \Core\Format::string($currentFeed->getTitle()) ?>
        					</option>
        				<? endforeach; ?>
        			</select>
    			</td>
    			<td>
        			<a class="btn btn-mini btn-danger" href="<?= self::cml(array('deleteGroup'=>true, 'groupID'=>$currentElement->getID())) ?>">
    					<i class="icon-trash icon-white"></i> <?= \Core\Language::main()->get('groups', 'delete') ?>
    				</a>
    			</td>
    		</tr>
    	<? endforeach; ?>
    	<? if(!count(self::$moduleVars['manager'])): ?>
    		<tr>
    			<td colspan="4" class="text-center">
    				<?= \Core\Language::main()->get('groups', 'noGroups') ?>
    			</td>
    		</tr>
    	<? endif; ?>
    </tbody>
</table>
<form method="post" action="<?= self::cml(array('changeGroups'=>true)) ?>" id="changeGroupForm"></form>

<form class="form-inline text-center" action="<?= self::cml(array('addGroup'=>true)) ?>" method="post">
     <input type="text" placeholder="<?= \Core\Language::main()->get('groups', 'groupName') ?>" name="groupName">
     <input class="btn" type="submit" value="<?= \Core\Language::main()->get('groups', 'addGroup') ?>">
</form>