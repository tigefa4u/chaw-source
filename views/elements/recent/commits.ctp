<?php
if (!empty($commits)):

	$li = null;
	foreach ($commits as $commit) :

		$project = null;
		if (!emptY($commit['Project']) && $commit['Project']['id'] !== $CurrentProject->id) {
			$project = ' in '. $html->link($commit['Project']['name'], $chaw->url($commit['Project'], array(
				'admin' => false, 'controller' => 'browser'
			)), array('class' => 'project'));
		}

		$li .= $html->tag('li',
			$chaw->commit($commit['Commit']['revision'], $commit['Project']) .
			 $project . "<br />[{$commit['Commit']['author']}] {$commit['Commit']['message']}"
		);

	endforeach;

	echo $html->tag('div',
		$html->tag('h4', 'Recent Commits') .$html->tag('ul', $li),
		array('class' => 'panel', 'escape' => false)
	);

endif;