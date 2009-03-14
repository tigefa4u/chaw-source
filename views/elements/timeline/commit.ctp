<li class="event <?php echo $zebra?>">

	<p class="metadata">
		<?php
			if (!empty($label)) {
				echo "<span class=\"type commit\">{$label}</span>";
			}
		?>
		<span class="date">
			<?php
				echo date("H:i", strtotime($data['Commit']['created']));
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
				if ($CurrentProject->repo->type == 'git'):
				 	if (strpos(strtolower($data['Commit']['message']), 'merge') !== false) {
						__("merged");
					} else {
						__("pushed");
					}
				else :
					__('committed');
				endif;
				?>
			</strong>
			<?php
				echo $chaw->commit($data['Commit']['revision'], $data['Project']);

				if (!empty($data['Project']['fork'])) {
					$project = "forks/{$data['Project']['fork']}/{$data['Project']['url']}/{$data['Branch']['name']}";
					echo  " to " . $html->link($project, $chaw->url($data['Project'], array(
						'admin' => false, 'controller' => 'source','action' => 'branches',
						$data['Branch']['name']
					)), array('class' => 'project'));
				} else  {

					if (!empty($data['Branch']['name'])) {
						echo " to " . $html->link($data['Branch']['name'], $chaw->url($data['Project'], array(
								'controller' => 'source', 'action' => 'branches',
								$data['Branch']['name']
						)));
					}

					if (!empty($data['Project']) && $data['Project']['id'] !== $CurrentProject->id) {
						echo ' in '. $html->link($data['Project']['name'], $chaw->url($data['Project'], array(
							'admin' => false, 'controller' => 'source'
						)), array('class' => 'project'));
					}

				}
				/*
				if (empty($CurrentProject->fork) && !empty($data['Project']['fork'])) {
					'(' . $chaw->admin('merge', $chaw->url($data['Project'], array(
						'controller' => 'repo', 'action' => 'merge', $data['Project']['fork']
					))) . ')';
				}
				*/
			?>
		</p>

		<p class="description">
			<?php echo $text->truncate($data['Commit']['message'], 80, '...', false, true); ?>
		</p>
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