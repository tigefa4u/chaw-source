<div class="svn index">
<h2><?php __('Svn');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($svn as $svn):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $svn['Svn'][''])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $svn['Svn'][''])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $svn['Svn']['']), null, sprintf(__('Are you sure you want to delete # %s?', true), $svn['Svn'][''])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('New Svn', true), array('action'=>'add')); ?></li>
	</ul>
</div>
