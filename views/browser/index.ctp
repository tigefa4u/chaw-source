<div class="browser index">

<h2>
	<?php echo $html->link(Configure::read('Project.url'), array('action' => 'index'))?>
	<?php
		$path = '/';
		foreach ((array)$args as $part):
			$path .= $part . '/';
			echo '/' . $html->link(' ' . $part . ' ', array($path));
		endforeach;
		echo '/ ' . $current;
	?>
</h2>

<?php echo $this->element('project_details'); ?>

<?php
	if (!empty($data['Content'])) :
		echo $this->render('view', false);
		return;
	endif;
?>

<table cellpadding="0" cellspacing="0">
	<tr>
		<th style="padding-left: 28px"><?php __('Name');?></th>
		<th><?php __('Author');?></th>
		<th><?php __('Message');?></th>
		<th><?php __('Date');?></th>
		<th><?php __('Commit');?></th>
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
			<td><?php echo $html->link($item['name'], array($item['path']), array('class' => 'folder'));?></td>
			<td><?php echo $item['info']['author'];?></td>
			<td><?php echo $item['info']['message'];?></td>
			<td><?php echo date("F d, Y", strtotime($item['info']['date']));?></td>
			<td><?php echo $html->link($item['info']['revision'], array('controller' => 'commits', 'action'=> 'view', $item['info']['revision']), array('class' => 'commit'));?></td>
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
			<td><?php echo $html->link($item['name'], array($item['path']), array('class' => 'file'));?></td>
			<td><?php echo $item['info']['author'];?></td>
			<td><?php echo $item['info']['message'];?></td>
			<td><?php echo date("F d, Y", strtotime($item['info']['date']));?></td>
			<td><?php echo $html->link($item['info']['revision'], array('controller' => 'commits', 'action'=> 'view', $item['info']['revision']), array('class' => 'commit'));?></td>
		</tr>
<?php
	endforeach;
?>

</table>

</div>