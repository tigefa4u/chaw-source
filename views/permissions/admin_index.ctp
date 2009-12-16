<?php //@todo embed variable text ?>
<div class="permissions form">
<?php echo $form->create(array('action' => $this->action));?>
	<fieldset>
 		<legend><?php __('Manage Permissions');?></legend>
		<?php
			echo $form->input('fine_grained', array('type' => 'textarea'));
		?>
	</fieldset>

<?php
	echo '<div class="submit">';
	echo '<input type="submit" value="'.__('Submit',true).'">';
	echo '<input type="submit" value="'.__('Reload Defaults',true).'" name="default">';
	echo '</div>';

echo $form->end();

?>
	<div class="help">
		<h4><?php __('Guide'); ?></h4>
		<p><?php __('access rights') ?></p>
		<p class="rule">
			r - <?php __('read') ?><br/>
			w - <?php __('write (c, u, d)') ?><br/>
			<?php
				$example = 'rw';
				if($CurrentProject->repo->type != 'svn'): $example = 'cr';
			?>
				c - <?php __('create') ?><br/>
				u - <?php __('update') ?><br/>
				d - <?php __('delete') ?><br/>
			<?php endif;?>
		</p>
		<p>`all` can `<?php echo $example; ?>` wiki</p>
		<em style="font-size: 11px">
			<?php __('with `*`, only logged in users can `write`') ?></em>
		<p class="rule">
			[wiki]<br/>
			* = <?php echo $example; ?>
		</p>
		<p>
			<?php __('use groups from') ?>
			<?php echo $chaw->admin('settings', array(
				'admin' => false, 'controller' => 'projects', 'action' => 'edit'
			))?>
		</p>
		<p class="rule">
			<?php echo join(', ', $groups);?>
		</p>
		<p>`<?php echo $groups['user'];?>` can `<?php echo $example; ?>` tickets</p>
		<p class="rule">
			[tickets]<br/>
			@<?php echo $groups['user'];?> = <?php echo $example; ?>
		</p>
		<p><?php __('create groups') ?></p>
		<p class="rule">
			[groups]<br/>
			some-group-name = user1, user2
		</p>
		<p>`some-group-name` can `<?php echo $example; ?>` tickets</p>
		<p class="rule">
			[tickets]<br/>
			@some-group-name = <?php echo $example; ?>
		</p>
	</div>
</div>
