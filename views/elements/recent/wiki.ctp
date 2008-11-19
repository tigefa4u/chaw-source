<?php
if (!empty($wiki)):

	$li = null;
	foreach ($wiki as $wik) :

		$url = array('admin' => false,
			'controller' => 'wiki', 'action' => 'index', $wik['Wiki']['slug']
		);

		$project = null;
		if (!emptY($wik['Project']) && $wik['Project']['id'] !== $CurrentProject->id) {
			$url = $chaw->url($wik['Project'], $url);
			$project = ' in '. $html->link($wik['Project']['name'], $chaw->url($wik['Project'], array(
				'admin' => false, 'controller' => 'browser'
			)), array('class' => 'project'));
		}

		$li .= $html->tag('li',
			$html->link($wik['Wiki']['slug'], $url) . ' updated by ' . $wik['User']['username']
			. $project
		);

	endforeach;

	echo $html->tag('div',
		$html->tag('h4', 'Wiki Updates') .$html->tag('ul', $li),
		array('class' => 'panel', 'escape' => false)
	);

endif;