<div class="wiki row <?php echo $zebra;?>">

	<h3 class="name">
		Wiki:
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
	</h3>

	<span class="description">
		<?php
			if ($data['Wiki']['created'] != $data['Wiki']['modified']):
				echo 'current revision modified';
			else :
				echo 'new revision created';
			endif;
		?>
	</span>

<?php if (!empty($this->params['isAdmin'])):?>
	<span class="admin">
		<?php echo $chaw->admin('remove', array('controller' => 'timeline', 'action' => 'remove', $data['Timeline']['id']));?>
	</span>
<?php endif;?>

	<span class="date">
		<?php echo $time->nice($data['Wiki']['created']);?>
	</span>

	<span class="author">
		<?php echo @$data['User']['username'];?>
	</span>

</div>