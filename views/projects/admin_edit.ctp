<?php
	echo $chaw->messages($messages);
?>
<div class="projects form">
<?php echo $form->create(array('action' => $this->action,
		'url' => (empty($this->params['admin'])) ? array('id' => false) : array()
));?>
	<fieldset class="main">
 		<legend><?php echo $title_for_layout; ?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('repo_type', array('disabled' => true));
		echo $form->input('name', array('disabled' => true));
		echo $form->hidden('url');
		echo $form->hidden('fork');
		echo $form->input('description');

		if ($CurrentProject->id == 1 && $this->params['isAdmin']) :
			echo $form->input('private');
			echo $form->input('active');
			echo $form->input('approved');
		endif;
	?>
	</fieldset>
	<fieldset class="options">
 		<legend><?php __('Groups')?></legend>
		<?php
			echo $form->input('config.groups', array('label' => false, 'type' => 'textarea'));
		?>
		<p><?php __('Comma seperated') ?></p>
	</fieldset>
	<fieldset class="options">
 		<legend><?php __('Tickets')?></legend>
		<?php
			echo $form->input('config.ticket.types', array('type' => 'textarea'));
			echo $form->input('config.ticket.priorities', array('type' => 'textarea'));
			echo $form->input('config.ticket.resolutions', array('type' => 'textarea'));
		?>
		<p><?php __('Comma seperated') ?></p>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>