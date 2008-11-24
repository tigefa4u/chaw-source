<?php
Configure::write('debug', 0);

function format($timeline) {
	$type = $timeline['Timeline']['model'];
	
	if (empty($timeline[$type])) {
		return array();
	}
	
	$data = $timeline[$type];
	
	switch ($type) {
		case 'Commit':
			$title = $data['revision']; //$chaw->commit($commit['Commit']['revision'], $commit['Project'])
			$link = array('controller' => 'commits', 'action' => 'view', $data['revision']);
			$pubDate = $data['commit_date'];
			$description = $data['message'];
			$author = $data['author'];
		break;
		case 'Wiki':
			$title = Inflector::humanize($data['slug']);
			$link = array('controller' => 'wiki', 'action' => 'view', $data['slug']);
			$pubDate = $data['modified'];
			$description = $data['content'];
			$author = $data['User']['username'];
		break;
		case 'Ticket':
			$title = $data['title'];
			$link = array('controller' => 'ticket', 'action' => 'view', $data['id']);
			$pubDate = $data['modified'];
			$description = $data['description'];
			$author = $data['Reporter']['username'];
		break;
		case 'Comment':
			$title = $data['Ticket']['title'];
			$link = array(
				'controller' => 'tickets', 'action' => 'view', $data['Ticket']['id']
			);;
			$pubDate = $data['Ticket']['modified'];
			$description = 'Comment on ' . $data['Ticket']['title'];
			$author = $data['User']['username'];
		break;

	}
	
	$pubDate = date('r', strtotime($pubDate));

	return compact('title', 'link', 'pubDate', 'description', 'author');
}

$this->set('channel', array(
	'title' => $CurrentProject->name . ' Timeline',
	'link' => $rssFeed
));
echo $rss->items($timeline, 'format');