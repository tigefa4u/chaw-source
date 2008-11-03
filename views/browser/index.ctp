<div class="browser index">

<h2>
	<?php echo $html->link(Configure::read('Project.url'), array('action' => 'index'))?>
	<?php
		$path = '/';
		foreach ((array)$args as $part):
			$path .= $part . ' / ';
			echo '/ ' . $html->link($part, array($path));
		endforeach;
		echo ' / ' . $current;
	?>
</h2>

<?php
	if (!empty($data['Content'])) :
		echo $this->render('view', false);
		return;
	endif;
?>

<table cellpadding="0" cellspacing="0">
	<tr>
		<th><?php __('Name');?></th>
		<th><?php __('Size');?></th>
		<th><?php __('Revision');?></th>
		<th><?php __('Author');?></th>
		<th><?php __('Message');?></th>
	</tr>
<?php
	$i = 0;
	foreach ((array)$data['Folder'] as $item):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="zebra"';
		}
?>
		<tr<?php echo $class?>>
			<td><?php echo $html->link($item['name'], array($item['path']));?></td>
			<td><?php echo $item['size']['num'];?> <?php echo $item['size']['ext'];?></td>
			<td><?php echo $item['info']['revision'];?></td>
			<td><?php echo $item['info']['author'];?></td>
			<td><?php echo $item['info']['message'];?></td>
		</tr>
<?php
	endforeach;
?>

<?php
	foreach ((array)$data['File'] as $item):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="zebra"';
		}
?>
		<tr<?php echo $class?>>
			<td><?php echo $html->link($item['name'], array($item['path']));?></td>
			<td><?php echo $item['size']['num'];?> <?php echo $item['size']['ext'];?></td>
			<td><?php echo $item['info']['revision'];?></td>
			<td><?php echo $item['info']['author'];?></td>
			<td><?php echo $item['info']['message'];?></td>
		</tr>
<?php
	endforeach;
?>

</table>

</div>