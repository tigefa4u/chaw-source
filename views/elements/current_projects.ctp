<?php
	if (empty($projects)) {
		return false;
	}

	if (!empty($projects)):

		$li = null;
		foreach ((array)$projects as $project):

			$url = null;
			if ($project['Project']['id'] != 1) {
				$url = $project['Project']['url'];
			}
			$fork = null;
			if (!empty($project['Project']['fork'])) {
				$fork = $project['Project']['fork'];
			}

			$li .= $html->tag('li',
				$html->link($project['Project']['name'], array(
					'admin' => false, 'project' => $url, 'fork'=> $fork,
					'controller' => 'browser', 'action' => 'index',
				))
			);

		endforeach;

		echo $html->tag('div',
			$html->tag('h4', 'Projects') .$html->tag('ul', $li),
			array('class' => 'panel', 'escape' => false)
		);

	endif;
?>