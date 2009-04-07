<li class="event <?php echo $zebra?>">

	<p class="metadata">
		<span class="type comment">
			<?php echo $data['Ticket']['type'];?>
		</span>
		<span class="date">
			<?php
				echo date("H:i", strtotime($data['Comment']['created']));
			?>
		</span>
		<?php
			if (!empty($data['Comment']['reason'])) :
				echo "<span class=\"small\"><strong>{$data['Comment']['reason']}</strong></span>";
			endif;
		?>
	<p>

	<div class="body">
		<!--
			<img width="16" height="16" src="http://www.gravatar.com/avatar.php?gravatar_id=5a973346f5546f3a840e1fcec0e9e4f1&size=16" alt="avatar"/>
		-->

		<p class="action">
			<span class="username">
				<?php echo $data['User']['username'];?>
			</span>
			<strong>
				<?php
					__('modified');
				?>
			</strong>
			<?php
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

				echo ' ' . $html->link($data['Ticket']['title'], $url);
				echo " ({$data['Ticket']['status']})" .  $project;
			?>
		</p>

		<div class="description">
			<?php
				if (!empty($data['Comment']['changes'])) {
					echo $chaw->changes($data['Comment']['changes']);
				}
				echo $text->truncate($data['Comment']['body'], 80, '...', false, true);
			?>
		</div>
	</div>

	<?php if (!empty($this->params['isAdmin'])):?>
		<span class="admin">
			<?php echo $chaw->admin(__('remove',true), array('controller' => 'timeline', 'action' => 'remove', $data['Timeline']['id']));?>
		</span>
	<?php endif;?>

</li>