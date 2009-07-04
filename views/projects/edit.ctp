<?php
	echo $chaw->messages($messages);
?>
<div class="projects form">
<?php echo $form->create(array('action' => $this->action,
		'url' => array('id' => false)
));?>
	<fieldset class="main">
 		<legend><?php echo $this->pageTitle; ?></legend>
	<?php
		echo $form->input('id');
		echo $form->hidden('url');
		echo $form->hidden('fork');

		echo $form->input('repo_type', array(
			'label' => __('Repo Type', true),
			'disabled' => true,
		));

		echo $form->input('name', array(
			'disabled' => true
		));

		if ($form->value('private') == 0) {
			echo $form->input('ohloh_project', array(
				'label' => '<a href="https://www.ohloh.net">https://www.ohloh.net/</a>p/',
				'div' => 'inline'

			));
		}
		echo $form->input('description');
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