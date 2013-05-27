<table class="table table-striped">
    <thead>
    	<tr>
    		<th><?= \Core\Language::main()->get('groups', 'tableID') ?></th>
    		<th><?= \Core\Language::main()->get('groups', 'tableName') ?></th>
    		<th><?= \Core\Language::main()->get('groups', 'tableFeeds') ?></th>
    		<th><?= \Core\Language::main()->get('groups', 'tableActions') ?></th>
    	</tr>
    </thead>
    <tbody>
    	<? foreach(self::$moduleVars['manager'] as $currentElement): ?>
    		<tr>
    			<td><?= \Core\Format::number($currentElement->getID()) ?></td>
    			<td><?= \Core\Format::string($currentElement->getTitle()) ?></td>
    			<td></td>
    			<td>
	    			<a class="btn btn-mini btn-danger" href="<?= self::cml(array('deleteGroup'=>true, 'groupID'=>$currentElement->getID())) ?>">
    					<i class="icon-trash icon-white"></i> Löschen
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

<form class="form-inline text-center" action="<?= self::cml(array('addGroup'=>true)) ?>" method="post">
     <input type="text" placeholder="<?= \Core\Language::main()->get('groups', 'groupName') ?>" name="groupName">
     <input class="btn" type="submit" value="<?= \Core\Language::main()->get('groups', 'addGroup') ?>">
</form>