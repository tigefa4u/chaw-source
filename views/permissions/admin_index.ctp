<div class="permissions form">
<?php echo $form->create(array('action' => $this->action));?>
	<fieldset>
 		<legend><?php __('Manage Permissions');?></legend>
		<?php
			echo $form->input('fine_grained', array('type' => 'textarea'));
		?>
	</fieldset>
<?php echo $form->end('Submit');?>
	<div class="help">
		<h4>Guide</h4>
		<p>access rights</p>
		<p class="rule">
			r - read<br/>
			w - write (c, u, d)<br/>
			<?php  
				$example = 'rw';
				if($CurrentProject->repo->type != 'svn'): $example = 'cr';
			?>
				c - create<br/>
				u - update<br/>
				d - delete<br/>
			<?php endif;?>
		</p>
		<p>`all` can `<?php echo $example; ?>` wiki</p>
		<em style="font-size: 11px">
			with `*`, only logged in users can `write`</em>
		<p class="rule">
			[wiki]<br/>
			* = <?php echo $example; ?>
		</pre>
		<p>
			use groups from 
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
		</pre>
		<p>create groups</p>
		<p class="rule">
			[groups]<br/>
			some-group-name = user1, user2
		</pre>
		<p>`some-group-name` can `<?php echo $example; ?>` tickets</p>
		<p class="rule">
			[tickets]<br/>
			@some-group-name = <?php echo $example; ?>
		</pre>
	</div>
</div>
