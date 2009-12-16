<?php
if (!empty($commits)):

	$li = null;
	foreach ($commits as $commit) :

		$project = null;
		if (!empty($commit['Project']) && $commit['Project']['id'] !== $CurrentProject->id) {
			$project = ' in '. $html->link($commit['Project']['name'], $chaw->url($commit['Project'], array(
				'admin' => false, 'controller' => 'source'
			)), array('class' => 'project'));
		}

		$li .= $html->tag('li',
			$chaw->commit($commit['Commit']['revision'], $commit['Project']) .
			 $project . "<br />[{$commit['Commit']['author']}] {$commit['Commit']['message']}"
		);

	endforeach;
	
	if (empty($title)) {
		$title = __('Recent Commits',true);
	}
	
	echo $html->tag('div',
		$html->tag('h4', $title) .$html->tag('ul', $li),
		array('class' => 'panel', 'escape' => false)
	);

endif;