<div class="users index">
	<div class="users-search">
		<?php echo $form->create(array('type' => 'get', 'action' => 'index', 'url' => $this->passedArgs)); ?>
			<fieldset>
				<?php
				echo $form->input('username', array(
					'label'=> __('Username', true),
				));
				?>
				<div class="submit">
					<input type="submit" value="search">
				</div>
			</fieldset>
		<?php echo $form->end(); ?>
	</div>

	<h2><?php __('Users');?></h2>
	<h4>
		<?php echo ($CurrentProject->id == 1) ? $chaw->admin(__('All Users', true), array('all' => true)) : null; ?>
		who fork, git clone, svn commit or <?php echo $chaw->admin('added', '#UserAddForm');?>
	</h4>

	<p>
		<?php
			$paginator->options(array('url'=> $this->passedArgs));
			echo $paginator->counter(array(
					'format' => __('(page {:page} of {:pages}, showing {:current} of {:count} users)', true)
			));
		?>
	</p>

	<?php if (!empty($groups)):?>
		<?php echo $form->create(array('action' => 'index')); ?>
	<?php endif; ?>

	<table cellpadding="0" cellspacing="0">
	<tr>
		<?php if (!empty($groups)):?>
			<th class="left">
				<?php echo $paginator->sort(__('Group',true), 'Permission.group');?>
			</th>
		<?php endif; ?>

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

			<?php if (!empty($groups)):?>
				<td class="left">
					<?php
						echo $form->hidden("Permission.{$i}.id", array('value' => $user['Permission']['id']));
						echo $form->select("Permission.{$i}.group", $groups, $user['Permission']['group'], array('empty' => false));
					?>
				</td>
			<?php endif; ?>
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

	<?php if (!empty($groups)):?>
		<p class="clear">
			groups are an easy way to setup <?php echo $chaw->admin('permissions', array('controller' => 'permissions'));?>
		</p>
		<?php echo $form->end('update');?>
	<?php endif; ?>

</div>
<div class="paging">
	<?php
		echo $paginator->prev('<< ' . __('previous', true));
		echo $paginator->numbers(array('before' => ' | ', 'after' => ' | '));
		echo $paginator->next(__('next', true) . ' >>');
	?>
</div>
<div class="clear"><!----></div>
<?php if (!empty($groups)):?>
	<div class="users add">
	<?php
		if (!empty($this->params['isAdmin'])):
			echo $form->create(array('action' => 'index', 'id' => 'UserAddForm'));
			echo '<fieldset><legend>Add User</legend>';
			echo $form->input('group');
			echo $form->input('username');
			echo $form->submit('add');
			echo '</fieldset>';
			echo $form->end();
		endif;
	?>
	</div>
<?php endif; ?>
