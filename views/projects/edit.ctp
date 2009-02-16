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
		echo $form->input('repo_type', array('disabled' => true,'label' => __('Repo Type',true)));
		echo $form->input('name', array('disabled' => true));
		echo $form->hidden('url');
		echo $form->hidden('fork');
		echo $form->input('description');
		if ($form->value('private') == 0) {
			echo $form->input('ohloh_project', array(
				'after' => '<small>' . __("the url for the project on", true) . ' <a href="http://ohloh.net">ohloh.net</a></small>'
			));
		}
	?>
	</fieldset>
	<fieldset class="options">
 		<legend>Options</legend>
	<?php
		echo $form->input('config.groups', array('type' => 'textarea'));
		echo $form->input('config.ticket.types', array('type' => 'textarea'));
		echo $form->input('config.ticket.priorities', array('type' => 'textarea'));
		echo $form->input('config.ticket.statuses', array('type' => 'textarea'));
		echo $form->input('config.ticket.resolutions', array('type' => 'textarea'));
	?>
	<p><?php __('Comma seperated') ?></p>

	</fieldset>
<?php echo $form->end('Submit');?>
</div>