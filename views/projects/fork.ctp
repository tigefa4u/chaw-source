<div class="fork confirm">
	<?php
		echo $form->create(array('action' => $this->action));
		echo $html->tag('fieldset',
			$html->tag('legend', "Are you ready to fork {$CurrentProject->name}")
			. "<p>A fork is a central repository for your copy of the project.</p>"
			. "<p>If you plan to contribute to the main project, then a fork may be just what you need.</p>"
			. "<p>Otherwise, you can always <strong>git clone</strong> {$CurrentProject->remote->git}:{$CurrentProject->url}.git</p>"
		 	. $form->hidden('project_id', array('value' => $CurrentProject->id))
		);
		
		echo '<div class="submit">';
		echo '<input type="submit" value="Go For It">';
		echo '<input type="submit" value="Cancel" name="cancel">';
		echo '</div>';
		
		echo $form->end();
	?>
</div>