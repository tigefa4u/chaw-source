<div class="project delete confirm">
	<?php
		echo $form->create(array('action' => $this->action));
		echo $html->tag('fieldset',
			$html->tag('legend', sprintf(__("Delete %s",true),$CurrentProject->name))
			. __("You are about to permanently delete this project.", true)
		 	. $form->hidden('id', array('value' => $CurrentProject->id))
		);

		echo '<div class="submit">';
		echo '<input type="submit" value="'.__('Go For It',true).'">';
		echo '<input type="submit" value="'.__('Cancel',true).'" name="cancel">';
		echo '</div>';

		echo $form->end();
	?>
</div>