<div class="projects fork">
	<?php
		echo $form->create(array('action' => $this->action));
		echo $html->tag('fieldset',
			$html->tag('legend', "Are you ready to fork {$CurrentProject->name}")
			. "when you fork you create a project for yourself."
		 	. $form->hidden('project_id', array('value' => $CurrentProject->id))
		);
		echo $form->end('Go for it');
	?>
</div>