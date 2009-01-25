<div class="comment row <?php echo $zebra;?>">

	<h3 class="name">
		<?php
			__('Change to:');
						
			$url = array('admin' => false,
				'controller' => 'tickets', 'action' => 'view', $data['Ticket']['number'],
				'#' => 'c' . $data['Comment']['id']
			);

			$project = null;
			if (!empty($data['Ticket']['Project']) && $data['Ticket']['Project']['id'] !== $CurrentProject->id) {
				$url = $chaw->url($data['Ticket']['Project'], $url);
				$project = ' in '. $html->link($data['Ticket']['Project']['name'], $chaw->url($data['Ticket']['Project'], array(
					'admin' => false, 'controller' => 'source'
				)), array('class' => 'project'));
			}

			echo ' ' . $html->link($data['Ticket']['title'], $url) . " <small>({$data['Ticket']['status']})</small>" . $project;?>
	</h3>

	<span class="description">
		<?php echo $text->truncate($data['Comment']['body'], 80, '...', false, true); ?>
	</span>

<?php if (!empty($this->params['isAdmin'])):?>
	<span class="admin">
		<?php echo $chaw->admin('remove', array('controller' => 'timeline', 'action' => 'remove', $data['Timeline']['id']));?>
	</span>
<?php endif;?>
	
	<span class="subtitle">
		<?php echo $data['Ticket']['type'];?>
	</span>
	
	<span class="date">
		<?php echo $time->nice($data['Comment']['created']);?>
	</span>

	<span class="author">
		<?php echo $data['User']['username']; ?>
	</span>

</div>