<div class="fork confirm">
	<?php
		echo $form->create(array('action' => $this->action));
		echo $html->tag('fieldset',
			$html->tag('legend', sprintf(__("Are you ready to fork %s",true),$CurrentProject->name))
			. __("<p>A fork is a central repository for your copy of the project.</p>",true)
			. __("<p>If you plan to contribute to the main project, then a fork may be just what you need.</p>",true)
			. sprintf(__("<p>Otherwise, you can always <strong>git clone</strong> %s:%s.git</p>",true), $CurrentProject->remote->git, $CurrentProject->url)
		 	. $form->hidden('project_id', array('value' => $CurrentProject->id))
		);

		echo '<div class="submit">';
		echo '<input type="submit" value="'.__('Go For It',true).'">';
		echo '<input type="submit" value="'.__('Cancel',true).'" name="cancel">';
		echo '</div>';

		echo $form->end();
	?>
</div>