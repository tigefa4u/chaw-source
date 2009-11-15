<h2 class="tickets crumbs">
	Tickets: <?php __(
		Inflector::humanize($current) . (!empty($user) ? "'s" : '')
	) ?>
</h2>
<div class="nav tabs right queues">
<ul>
<?php

$active = null;
if (!$type && !$user) {
	$active = array('class' => 'active');
}
$links = array(
	$html->link(__('all', true), array_unique(array_merge($this->passedArgs, array(
		'user' => null, 'status' => null, 'type' => null, 0 => 'all',
	))), $active)
);

if (!empty($CurrentUser->username)) {
	$active = null;
	if ($CurrentUser->username == $user) {
		$active = array('class' => 'active');
	}
	$links[] = $html->link(__('mine', true), array_merge($this->passedArgs, array(
		'user' => $CurrentUser->username
	)), $active);
}
echo '<li>'. join('</li><li>', $links) . '</li>';

?>
	<li class="new-ticket"><?php echo $html->link(__('new ticket', true), array('controller' => 'tickets', 'action' => 'add')); ?></li>
</ul>
</div>
<?php echo $form->create(array('type' => 'get', 'action' => 'index', 'url' => $this->passedArgs)); ?>
<fieldset class="ticket-search">
	<div class="input-row">
	<?php
		echo $form->label('Ticket.type.rfc', __('type', true), 'title');
		echo $form->select('Ticket.type', $types, null, array('multiple' => 'checkbox'));
	?>
	</div>

	<div class="input-row">
	<?php
		echo $form->label('Ticket.priority.low', __('priority', true), 'title');
		echo $form->select('Ticket.priority', $priorities, null, array('multiple' => 'checkbox'));
	?>
	</div>

	<div class="input-row">
	<?php
		echo $form->label('Ticket.statuses.closed', __('status', true), 'title');
		echo $form->select('Ticket.status', $statuses, null, array('multiple' => 'checkbox'));
	?>
	</div>

	<?php echo $form->submit('update'); ?>
</fieldset>
<?php echo $form->end(); ?>
<?php
$paginator->options(array('url' => $this->passedArgs));

if ($this->params['paging']['Ticket']['page'] > 0) {
	echo '<div class="paging">';
	echo $paginator->counter(array(
		'format' => __('(page {:page} of {:pages}, showing {:current} of {:count} tickets)', true)
	));
	echo '</div>';
}

?>
<div class="tickets index">
<table class="smooth" cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('#', 'number'); ?></th>
	<th class="left"><?php echo $paginator->sort(__('Title',true), 'title');?></th>
	<th><?php echo $paginator->sort(__('Type', true), 'type'); ?></th>
	<th><?php echo $paginator->sort(__('Priority', true), 'priority'); ?></th>
	<?php if($status != 'closed'): ?>
		<th><?php echo $paginator->sort(__('Reporter', true), 'reporter'); ?></th>
	<?php endif; ?>
	<?php if($status != 'pending'): ?>
		<th><?php echo $paginator->sort(__('Owner', true), 'owner'); ?></th>
	<?php endif; ?>
	<?php if(!$status): ?>
		<th><?php echo $paginator->sort(__('Status', true),'status');?></th>
	<?php endif; ?>
	<?php if($status == 'closed'): ?>
		<th><?php echo $paginator->sort(__('Resolution', true), 'resolution');?></th>
	<?php endif; ?>
	<th><?php echo $paginator->sort(__('Version', true), 'version_id'); ?></th>
	<th><?php echo $paginator->sort(__('Created', true), 'created');?></th>
</tr>
<?php
$i = 0;
foreach ($tickets as $ticket):
	$class = null;
	if (++$i % 2 == 0) {
		$class = ' class="zebra"';
	}
?>
	<tr<?php echo $class;?>>
		<td class="number">
			<?php echo $html->link(
				$ticket['Ticket']['number'],
				array('controller'=> 'tickets', 'action'=>'view', $ticket['Ticket']['number'])
			); ?>
		</td>

		<td class="title left">
			<?php echo $html->link(
				$ticket['Ticket']['title'],
				array('controller'=> 'tickets', 'action'=>'view', $ticket['Ticket']['number'])
			); ?>
		</td>

		<td><?php echo $ticket['Ticket']['type']; ?></td>

		<td><?php echo $ticket['Ticket']['priority']; ?></td>

		<?php if($status != 'closed'): ?>
			<td><?php echo $ticket['Reporter']['username']; ?></td>
		<?php endif; ?>

		<?php if($status != 'pending'): ?>
			<td><?php echo $ticket['Owner']['username']; ?></td>
		<?php endif; ?>

		<?php if (!$status): ?>
			<td><?php echo $ticket['Ticket']['status']; ?></td>
		<?php endif; ?>

		<?php if($status == 'closed'): ?>
			<td><?php echo $ticket['Ticket']['resolution'];?></td>
		<?php endif; ?>

		<td><?php
			if (!empty($ticket['Version']['title'])):
				echo $html->link($ticket['Version']['title'], array(
						'controller' => 'versions', 'action' => 'view', $ticket['Version']['id']
				));
			endif; ?>
		</td>

		<td nowrap>
			<?php echo $time->format('m.d.y', $ticket['Ticket']['created']); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>

<div class="paging white">
	<?php
		echo $paginator->prev('<< ' . __('previous', true));
		echo $paginator->numbers(array('before' => ' | ', 'after' => ' | '));
		echo $paginator->next(__('next', true) . ' >>');
	?>
</div>
