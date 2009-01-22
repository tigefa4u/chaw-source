<?php
Configure::write('debug', 0);
function format($project) {
	$url = null;
	if ($project['Project']['id'] != 1) {
		$url = $project['Project']['url'];
	}
	$fork = null;
	if (!empty($project['Project']['fork'])) {
		$fork = $project['Project']['fork'];
	}

	return array(
	        'title' => $project['Project']['name'],
	        'link'  => array(
				'admin' => false, 'project' => $url, 'fork'=> $fork,
				'controller' => 'browser', 'action' => 'index',
			),
			'description' => $project['Project']['description'],
	        'author' => $project['User']['username'],
	        'pubDate' => date('r', strtotime($project['Project']['created']))
	);

}
$this->set('channel', array(
	'title' => $CurrentProject->name . ' Projects',
	'link' => $rssFeed
));
echo $rss->items($projects, 'format');