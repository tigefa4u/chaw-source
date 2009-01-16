<div class="projects index">
<h2><?php __('Projects');?></h2>
<div class="page-navigation">
	<?php
		echo $chaw->type('pending', array(
			'controller' => 'projects', 'action' => 'index',
		)) . ' | ';
		
		echo $chaw->type('all', array(
			'controller' => 'projects', 'action' => 'index',
		)) . ' | ';

		echo $chaw->type('public', array(
			'controller' => 'projects', 'action' => 'index',
		)) . ' | ';

		echo $chaw->type('forks', array(
			'controller' => 'projects', 'action' => 'index',
		)) . ' | ';
	
	?>
</div>
<p>
<?php
$paginator->options(array('url'=> $this->passedArgs));
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th width="20"><?php echo $paginator->sort('Type', 'repo_type');?></th>
	<th><?php echo $paginator->sort('name');?></th>
	<th><?php echo $paginator->sort('user_id');?></th>
	<th><?php echo $paginator->sort('private');?></th>
	<th><?php echo $paginator->sort('approved');?></th>
	<th><?php echo $paginator->sort('active');?></th>
	<th class="actions">&nbsp;</th>
</tr>
<?php
$i = 0;
foreach ($projects as $project):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
	$url = null;
	if ($project['Project']['id'] != 1) {
		$url = $project['Project']['url'];
	}
	$fork = null;
	if (!empty($project['Project']['fork'])) {
		$fork = $project['Project']['fork'];
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $project['Project']['repo_type']; ?>
		</td>
		<td>
			<?php
				echo $html->link($project['Project']['name'], array(
					'admin' => false, 'project' => $url, 'fork'=> $fork,
					'controller' => 'source', 'action' => 'index',
				));?>
		</td>
		<td>
			<?php echo $project['User']['username']; ?>
		</td>
		<td>
			<?php echo ($project['Project']['private'] != 0) ? $project['Project']['private'] : null; ?>
		</td>
		<td class="actions">
			<?php
				echo $chaw->toggle($project['Project']['approved'], array(
					'approve', 'reject',
					'url' => array(
						'admin' => true, 'project' => false, 'fork' => false,
						'controller' => 'projects',	$project['Project']['id']
					)
				)); ?>
		</td>
		<td class="actions">
			<?php
				echo $chaw->toggle($project['Project']['active'], array(
					'activate', 'deactivate',
					'url' => array(
						'admin' => true, 'project' => false, 'fork' => false,
						'controller' => 'projects',	$project['Project']['id']
					)
				)); ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('edit', true), array('action'=>'edit', $project['Project']['id'])); ?>
			<?php //echo $html->link(__('Delete', true), array('action'=>'delete', $project['Project']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $project['Project']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?> |
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>