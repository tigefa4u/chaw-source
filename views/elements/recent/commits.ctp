<?php
if (!empty($commits)):

	$li = null;
	foreach ($commits as $commit) :

		$li .= $html->tag('li',
			$html->link($commit['Commit']['revision'], array('admin' => false,
				'controller' => 'commits', 'action' => 'view', $commit['Commit']['revision']
			)) .
			"<br />[{$commit['Commit']['author']}] {$commit['Commit']['message']}"

		);

	endforeach;

	echo $html->tag('div',
		$html->tag('h4', 'Recent Commits') .$html->tag('ul', $li),
		array('class' => 'panel', 'escape' => false)
	);

endif;