<li class="event <?php echo $zebra?>">

	<p class="metadata">
		<span class="type ticket">
			<?php echo $data['Ticket']['type'];?>
		</span>
		<span class="date">
			<?php
				echo date("H:i", strtotime($data['Ticket']['created']));
			?>
		</span>
	<p>

	<div class="body">
		<!--
			<img width="16" height="16" src="http://www.gravatar.com/avatar.php?gravatar_id=5a973346f5546f3a840e1fcec0e9e4f1&size=16" alt="avatar"/>
		-->

		<p class="action">

			<span class="username">
				<?php echo $data['Reporter']['username']; ?>
			</span>
			<strong>
				<?php
					__("created");
				?>
			</strong>
			<?php
				$url = array('admin' => false,
					'controller' => 'tickets', 'action' => 'view', $data['Ticket']['number']
				);

				$project = null;
				if (!empty($data['Project']) && $data['Project']['id'] !== $CurrentProject->id) {
					$url = $chaw->url($data['Project'], $url);
					$project = ' in '. $html->link($data['Project']['name'], $chaw->url($data['Project'], array(
						'admin' => false, 'controller' => 'source'
					)), array('class' => 'project'));
				}

				echo $html->link($data['Ticket']['title'], $url) . $project;
			?>
		</p>

		<p class="description"><?php 
			echo $text->truncate($data['Ticket']['description'], 80, '...', false, true); 
		?></p>
	</div>

	<?php if (!empty($this->params['isAdmin'])):?>
		<span class="admin">
			<?php echo $chaw->admin(__('remove',true), array('controller' => 'timeline', 'action' => 'remove', $data['Timeline']['id']));?>
		</span>
	<?php endif;?>

</li>