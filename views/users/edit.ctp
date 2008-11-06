<div class="users form">
<?php echo $form->create('User');?>
	<fieldset>
 		<legend><?php __('Update Info');?></legend>
	<?php
		echo $form->input('id');
		echo $form->hidden('username');
		echo $form->input('username', array('disabled' => true));
		echo $form->input('email');
	?>
	</fieldset>
	<fieldset>
 		<legend><?php __('Ssh Keys');?></legend>
		<?php
			foreach ((array)$sshKeys as $type => $keys) {
				$fields = null;

				foreach ((array)$keys as $i => $sshKey) {

					$fields .= $form->checkbox("Key.{$type}.{$i}.chosen", array(
						'value' => 1,
					));

					$fields .= $form->text("Key.{$type}.{$i}.content", array(
						'value' => $sshKey,
						'disabled' => false,
						'class' => 'text'
					));

				}
				$legend = $html->tag('legend', $type);

				if ($fields !== null) {
					echo $html->tag('fieldset',
						$legend .
						$html->tag('div', $fields, array('class' => 'checkbox')) .
						$form->submit('delete')
					);
			}
			}
		?>

		<fieldset>
	 		<legend><?php __('New');?></legend>
			<?php
				echo $form->input('SshKey.type', array(
					'label' => false,
					'after' => $form->text('SshKey.content', array('type' => 'text', 'class' => 'text'))
				));
			?>
		</fieldset>

	</fieldset>
<?php echo $form->end('Submit');?>
</div>
