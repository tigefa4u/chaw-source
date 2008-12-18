<div class="forgotten">

	<h2 style="clear:both">Forgetten Password</h2>

	<?php echo $form->create(array('action' => $this->action));?>
		<fieldset style="float:left;">
		<?php
			echo $form->input('username');
		?>
		</fieldset>
		<p style="float:left;font-size: 200%; margin: 72px 20px">
			OR
		</p>
		<fieldset style="float:left;">
		<?php
			echo $form->input('email');
		?>
		</fieldset>
	<?php echo $form->end('Submit');?>

</div>
