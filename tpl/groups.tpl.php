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
     <input type="text" placeholder="<?= \Core\Language::main()->get('groups', 'groupName') ?>" name="feedURL">
     <input class="btn" type="submit" value="<?= \Core\Language::main()->get('groups', 'addGroup') ?>">
</form>