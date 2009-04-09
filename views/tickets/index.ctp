<h2>
	<?php __(
		Inflector::humanize($current) .
		(!empty($this->params['named']['user']) ? "'s" : '') . ' ' .
		(!empty($this->params['named']['type']) ? $this->params['named']['type'] : '')
	) ?>
	<?php __('Tickets') ?>
</h2>

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

	<?php echo $form->submit('update'); ?>
</fieldset>
<?php echo $form->end(); ?>
<div class="queues">
<?php

$active = null;
if (empty($this->passedArgs['type']) && empty($this->passedArgs['user'])) {
	$active = array('class' => 'active');
}
$links = array(
	$html->link(__('all', true), array_merge($this->passedArgs, array(
		'user' => null, 'status' => null, 'type' => 'all'
	)), $active)
);

if (!empty($CurrentUser->username)) {
	$active = null;
	if (!empty($this->passedArgs['user']) && $CurrentUser->username == $this->passedArgs['user']) {
		$active = array('class' => 'active');
	}
	$links[] = $html->link(__('mine', true), array_merge($this->passedArgs, array(
		'user' => $CurrentUser->username
	)), $active);
}

foreach ($statuses as $status) {
	$active = null;
	if (!empty($this->passedArgs['status']) && $status == $this->passedArgs['status']) {
		$active = array('class' => 'active');
	}
	$links[] = $html->link(__($status, true), array_merge($this->passedArgs, array(
		'status' => $status
	)), $active);
}
echo join(' | ', $links);

?>
</div>
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
	<th><?php echo $paginator->sort(__('Version', true), 'version_id'); ?></th>
	<th><?php echo $paginator->sort(__('Type', true), 'type'); ?></th>
	<th><?php echo $paginator->sort(__('Priority', true), 'priority'); ?></th>
	<th><?php echo $paginator->sort(__('Reporter', true), 'reporter'); ?></th>
	<th><?php echo $paginator->sort(__('Owner', true), 'owner'); ?></th>
	<?php if(empty($this->passedArgs['status'])): ?>
		<th><?php echo $paginator->sort(__('Status', true),'status');?></th>
	<?php endif; ?>
	<?php if(!empty($this->passedArgs['status']) && $this->passedArgs['status'] == 'closed'): ?>
		<th><?php echo $paginator->sort(__('Resolution', true),'resolution');?></th>
	<?php endif; ?>
	<th class="left"><?php echo $paginator->sort(__('Title',true), 'title');?></th>
	<th><?php echo $paginator->sort(__('Created', true), 'created');?></th>
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
		<td class="number">
			<?php echo $html->link(
				$ticket['Ticket']['number'],
				array('controller'=> 'tickets', 'action'=>'view', $ticket['Ticket']['number'])
			); ?>
		</td>

		<td><?php
			if (!empty($ticket['Version']['title'])):
				echo $html->link(
					$ticket['Version']['title'],
					array('controller' => 'versions', 'action' => 'view', $ticket['Version']['id'])
				);
			endif; ?>
		</td>

		<td><?php echo $ticket['Ticket']['type']; ?></td>

		<td><?php echo $ticket['Ticket']['priority']; ?></td>

		<td><?php echo $ticket['Reporter']['username']; ?></td>

		<td><?php echo $ticket['Owner']['username']; ?></td>

		<?php if (empty($this->passedArgs['status'])): ?>
			<td><?php echo $ticket['Ticket']['status']; ?></td>
		<?php endif; ?>

		<td class="title left">
			<?php echo $html->link(
				$ticket['Ticket']['title'],
				array('controller'=> 'tickets', 'action'=>'view', $ticket['Ticket']['number'])
			); ?>
		</td>
		<td nowrap>
			<?php echo $time->format('m.d.y', $ticket['Ticket']['created']); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>

<div class="paging">
	<?php echo $paginator->prev('<< ' . __('previous', true), array(), null, array('class' => 'disabled'));?>
	| <?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
</div>

<?php echo $html->link(__('New Ticket', true), array('controller' => 'tickets', 'action' => 'add')); ?>