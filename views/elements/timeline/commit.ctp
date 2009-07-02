<li class="event <?php echo $zebra?>">

	<p class="metadata">
		<?php
			if (!empty($label)) {
				echo "<span class=\"type commit\">{$label}</span>";
			}
		?>
		<span class="date">
			<?php
				echo date("H:i", strtotime($data['Timeline']['created']));
			?>
		</span>
	<p>

	<div class="body">
		<!--
			<img width="16" height="16" src="http://www.gravatar.com/avatar.php?gravatar_id=5a973346f5546f3a840e1fcec0e9e4f1&size=16" alt="avatar"/>
		-->

		<p class="action">
			<span class="username">
				<?php echo (!empty($data['User']['username'])) ? $data['User']['username'] : $data['Commit']['author'];?>
			</span>
			<strong>
				<?php
				if ($CurrentProject->repo->type == 'git') {
					if ($data['Timeline']['event'] == 'pushed') {
					 	if (strpos(strtolower($data['Commit']['message']), 'merge') !== false) {
							__("merged");
						} else {
							__("pushed");
						}
						if (!empty($data['Timeline']['data'])) {
							$link = ' ' . $data['Commit']['changes'];
							if (!empty($data['Commit']['changes']) && strlen($data['Commit']['changes']) > 40) {
								$link = ' ' . $html->link($data['Timeline']['data'], array(
									'controller' => 'commits', 'logs', $data['Commit']['changes']
								));
							}
							echo $link . ' ' . __("commits", true);
						}
					} else if ($data['Timeline']['event'] == 'created') {
						__("created");
					}

				} else {
					__('committed');
				}
				?>
			</strong>
			<?php
				if (empty($data['Timeline']['data'])) {
					echo $chaw->commit($data['Commit']['revision'], $data['Project']) . ' ';
					__('to');
				}

				if (!empty($data['Commit']['branch'])) {
					echo " " . $html->link($data['Commit']['branch'], $chaw->url($data['Project'], array(
							'controller' => 'source', 'action' => 'branches',
							$data['Commit']['branch']
					)));
				}

				if (!empty($data['Project']) && $data['Project']['id'] !== $CurrentProject->id) {
					__('in');
					echo ' '. $html->link($data['Project']['name'], $chaw->url($data['Project'], array(
						'admin' => false, 'controller' => 'source'
					)), array('class' => 'project'));
				}

			?>
		</p>

		<?php if (empty($data['Timeline']['data'])) :?>
			<p class="description"><?php
				echo $text->truncate($data['Commit']['message'], 80, '...', false, true);
			?></p>
		<?php endif;?>

	</div>

	<?php if (!empty($this->params['isAdmin'])):?>
		<span class="admin">
			<?php
				if ($this->name == 'Commits') {
					echo $chaw->admin(__('remove',true), array('controller' => 'commits', 'action' => 'remove', $data['Commit']['id']));
				} else {
					echo $chaw->admin(__('remove',true), array('controller' => 'timeline', 'action' => 'remove', $data['Timeline']['id']));
				}
			?>
		</span>
	<?php endif;?>

</li>