<li class="event <?php echo $zebra?>">

	<p class="metadata">
		<span class="type wiki">
			<?php echo (isset($label)) ? $label . ': ' : null;?>
		</span>
		<span class="date">
			<?php
				echo date("H:i", strtotime($data['Wiki']['created']));
			?>
		</span>
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
					if ($data['Wiki']['created'] != $data['Wiki']['modified']):
						__('modified');
					else :
						__('created');
					endif;
				?>
			</strong>
			<?php
				$url = array('admin' => false,
					'controller' => 'wiki', 'action' => 'index', $data['Wiki']['path'], $data['Wiki']['slug']
				);

				$project = null;
				if (!emptY($data['Project']) && $data['Project']['id'] !== $CurrentProject->id) {
					$url = $chaw->url($data['Project'], $url);
					if (!empty($data['Project']['fork'])) {
						$project = "forks/{$data['Project']['fork']}/";
					}
					$project = $data['Project']['url'] . '/';
				}
				$title = $project . ltrim($data['Wiki']['path'] . '/' . $data['Wiki']['slug'], '/');
				echo $html->link($title, $url);
			?>
		</p>
	</div>

	<?php if (!empty($this->params['isAdmin'])):?>
		<span class="admin">
			<?php echo $chaw->admin(__('remove',true), array('controller' => 'timeline', 'action' => 'remove', $data['Timeline']['id']));?>
		</span>
	<?php endif;?>

</li>