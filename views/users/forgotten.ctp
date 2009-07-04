<div class="forgotten">

	<h2 style="clear:both"><?php __('Forgotten Password') ?></h2>

	<?php echo $form->create(array('action' => $this->action));?>
		<fieldset style="float:left;">
		<?php
			echo $form->input('username',array(
				'label'=> __('Username',true)
			));
		?>
		</fieldset>
		<p style="float:left;font-size: 200%; margin: 72px 20px">
			OR
		</p>
		<fieldset style="float:left;">
		<?php
			echo $form->input('email',array(
				'label'=> __('Email',true)
			));
		?>
		</fieldset>
	<?php echo $form->end(__('Submit',true));?>

</div>
