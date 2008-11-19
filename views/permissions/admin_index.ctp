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
			c - create<br/>
			u - update<br/>
			d - delete<br/>
		</p>
		<p>all users can `cru` wiki</p>
		<p class="rule">
			[wiki]<br/>
			* = cru
		</pre>
		<p>create groups</p>
		<p class="rule">
			[groups]<br/>
			some-group-name = user1, user2, user3
		</pre>
		<p>some-group-name can `cr` tickets</p>
		<p class="rule">
			[tickets]<br/>
			@some-group-name = cr
		</pre>
	</div>
</div>
