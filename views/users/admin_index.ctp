<div class="users index">
<h2><?php __('Users');?></h2>
<h4>
	<?php echo ($CurrentProject->id == 1) ? $chaw->admin(__('All Users', true), array('all'=>true)) : null; ?> 
	<?php __('who fork, git clone, svn commit or') ?> <?php echo $chaw->admin(__('add new user',true), '#UserAddForm');?>
</h4>
<p>
<?php
$paginator->options(array('url'=> $this->passedArgs));
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>

<?php echo $form->create(array('action' => 'index')); ?>
<table cellpadding="0" cellspacing="0">
<tr>
	<th class="left"><?php echo $paginator->sort('Group', 'Permission.group');?></th>
	<th><?php echo $paginator->sort(__('Username',true),'username');?></th>
	<th><?php echo $paginator->sort(__('Email',true),'email');?></th>
	<th><?php echo $paginator->sort(__('Last Login',true),'last_login');?></th>
	<th>&nbsp;</th>
</tr>
<?php
$i = 0;
foreach ($users as $i => $user):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td class="left">
			<?php
				echo $form->hidden("Permission.{$i}.id", array('value' => $user['Permission']['id']));
				echo $form->select("Permission.{$i}.group", $groups, $user['Permission']['group'], array(), false);
			?>
		</td>
		<td>
			<?php echo $user['User']['username']; ?>
		</td>
		<td>
			<?php echo $user['User']['email']; ?>
		</td>
		<td>
			<?php echo $user['User']['last_login']; ?>
		</td>
		<td class="actions">
			<?php 
				if (!empty($this->passedArgs['all']) && !empty($this->params['isAdmin'])) {
					echo $chaw->admin(__('edit',true), array('controller' => 'users', 'action' => 'edit', $user['User']['id']));
					echo $chaw->admin(__('remove',true), array('controller' => 'users', 'action' => 'remove', $user['User']['id'])); 
				} else {
					echo $chaw->admin(__('remove',true), array('controller' => 'permissions', 'action' => 'remove', $user['Permission']['id'])); 
				}
			?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
<p>
	<?php __('groups are an easy way to setup') ?> <?php echo $chaw->admin('permissions', array('controller' => 'permissions'));?>
</p>
<?php echo $form->end(__('update',true));?>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
<div class="users add">
<?php
	if (!empty($this->params['isAdmin'])):
		echo $form->create(array('action' => 'index', 'id' => 'UserAddForm'));
		echo '<fieldset><legend>'.__('Add User',true).'</legend>';
		echo $form->input('group',array('label'=>array('labeltext'=>__('Group',true))));
		echo $form->input('username',array('label'=>array('labeltext'=>__('Username',true))));
		echo '</fieldset>';
		echo $form->end(__('add',true));
	endif;
?>
</div>