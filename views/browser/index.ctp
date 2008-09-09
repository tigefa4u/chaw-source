<div class="page">
	<h2>
		<?php echo $html->link('..', array('action' => 'index'))?>
		<?php
			$path = '/';
			foreach ((array)$args as $part):
				echo '/' . $html->link($part, array($path . $part));
			endforeach;

			echo '/' . $current;
		?>
	</h2>

<table class="downloads">
	<tr>
		<th><?php __('Name');?></th>
		<th><?php __('MD5');?></th>
		<th><?php __('Size');?></th>
	</tr>
<?php
	$i = 0;
	foreach ($data['Folder'] as $download):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="zebra"';
		}
?>
		<tr<?php echo $class?>>
			<td><?php echo $html->link($download['name'], array($download['path']), array('class' => 'dir'));?></td>
			<td>&nbsp</td>
			<td><?php echo $download['size']['num'];?> <?php echo $download['size']['ext'];?></td>
		</tr>
<?php
	endforeach;
?>

<?php
	foreach ($data['File'] as $download):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="zebra"';
		}
?>
		<tr<?php echo $class?>>
			<td><?php echo $html->link($download['name'], $download['path']);?></td>
			<td><?php echo $download['md5'];?></td>
			<td><?php echo $download['size']['num'];?> <?php echo $download['size']['ext'];?></td>
		</tr>
<?php
	endforeach;
?>

</table>