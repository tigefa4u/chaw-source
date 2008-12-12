<h2>
	<?php echo Inflector::humanize($current)?>
	Tickets
</h2>
<?php
	$links = array();
	foreach ($statuses as $status) {
		$links[] = $html->link($status, array('status' => $status));
	}
	echo join(' | ', $links);
?>
<div class="tickets index">
<p>
<?php

$paginator->options(array('url' => $this->passedArgs));

if ($this->params['paging']['Ticket']['page'] > 0) {
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
}
?></p>
<table class="smooth" cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('#', 'number');?></th>
	<th><?php echo $paginator->sort('status');?></th>
	<th><?php echo $paginator->sort('title');?></th>
	<th><?php echo $paginator->sort('type');?></th>
	<th><?php echo $paginator->sort('priority');?></th>
	<th><?php echo $paginator->sort('version_id');?></th>
	<th><?php echo $paginator->sort('created');?></th>
</tr>
<?php
$i = 0;
foreach ($tickets as $ticket):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $ticket['Ticket']['number']; ?>
		</td>
		<td>
			<?php echo $ticket['Ticket']['status']; ?>
		</td>
		<td>
			<?php echo $html->link($ticket['Ticket']['title'], array('controller'=> 'tickets', 'action'=>'view', $ticket['Ticket']['number'])); ?>
		</td>
		<td>
			<?php echo $ticket['Ticket']['type']; ?>
		</td>
		<td>
			<?php echo $ticket['Ticket']['priority']; ?>
		</td>
		<td>
			<?php if (!empty($ticket['Version']['title'])):?>

				<?php echo $html->link($ticket['Version']['title'], array('controller'=> 'versions', 'action'=>'view', $ticket['Version']['id'])); ?>

			<?php endif;?>
		</td>
		<td>
			<?php echo $ticket['Ticket']['created']; ?>
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

<?php echo $html->link('New Ticket', array('controller' => 'tickets', 'action'=>'add')); ?>