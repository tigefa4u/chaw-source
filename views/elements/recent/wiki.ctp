<?php
if (!empty($wiki)):

	$li = null;
	foreach ($wiki as $data) :

		$url = array('admin' => false,
			'controller' => 'wiki', 'action' => 'index', $data['Wiki']['path'], $data['Wiki']['slug']
		);

		$project = null;
		if (!empty($data['Project']) && $data['Project']['id'] !== $CurrentProject->id) {
			$url = $chaw->url($data['Project'], $url);
			$project = ' in '. $html->link($data['Project']['name'], $chaw->url($data['Project'], array(
				'admin' => false, 'controller' => 'wiki'
			)), array('class' => 'project'));
		}

		$title = ltrim($data['Wiki']['path'] . '/' . $data['Wiki']['slug'], '/');

		$li .= $html->tag('li',
			$html->link($title, $url) 
			. ' updated by ' . $data['User']['username']
			. $project
		);

	endforeach;

	echo $html->tag('div',
		$html->tag('h4', __('Wiki Updates',true)) .$html->tag('ul', $li),
		array('class' => 'panel', 'escape' => false)
	);

endif;