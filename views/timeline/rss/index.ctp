<?php
Configure::write('debug', 0);

$this->set('channel', array(
	'title' => "{$CurrentProject->name}/Timeline",
	'link' => $rssFeed
));

foreach ($timeline as $data) {
	$type = $data['Timeline']['model'];

	if (empty($data[$type])) {
		continue;
	}

	switch ($type) {
		case 'Commit':
			$title = "{$type}/" . $data[$type]['revision']; //$chaw->commit($commit['Commit']['revision'], $commit['Project'])
			if (!empty($data['Commit']['branch'])) {
				$title = "Branches/" . $data['Commit']['branch'] . "/" . $title;
			}
			$link = array('controller' => 'commits', 'action' => 'view', $data[$type]['revision']);
			$pubDate = $data[$type]['created'];
			$description = $data[$type]['message'];
			$author = !empty($data['User']['username']) ? $data['User']['username'] : $data['Commit']['author'];
		break;
		case 'Wiki':
			$path = null;
			if (!empty($data[$type]['path']) && $data[$type]['path'] != '/') {
				$path = $data[$type]['path'];
			}
			$title = $type . $path . '/' . $data[$type]['slug'];
			$link = array('controller' => 'wiki', 'action' => 'index', $data[$type]['path'], $data[$type]['slug']);
			$pubDate = $data[$type]['created'];
			$description = $text->truncate(nl2br($data[$type]['content']), 200, array(
				'exact' => true, 'html' => false
			));
			$author = $data['User']['username'];
		break;
		case 'Ticket':
			$status = $data['Ticket']['status'];
			if (!empty($data['Comment']['reason'])) {
				$status = $data['Comment']['reason'];
			}
			$status = Inflector::humanize($status);
			$type = strtoupper(Inflector::humanize($data['Ticket']['type']));
			$title = "{$status}/{$type}/{$data['Ticket']['title']}";
			$link = array('controller' => 'tickets', 'action' => 'view', $data['Ticket']['number']);
			$pubDate = $data['Ticket']['created'];
			$description = $text->truncate(nl2br($data['Ticket']['description']), 200, array(
				'exact' => true, 'html' => false
			));
			$author = $data['Reporter']['username'];
		break;
		case 'Comment':
			$status = $data['Ticket']['status'];
			if (!empty($data['Comment']['reason'])) {
				$status = $data['Comment']['reason'];
			}
			$status = Inflector::humanize($status);
			$type = strtoupper(Inflector::humanize($data['Ticket']['type']));
			$title = "Comment/{$status}/{$type}/{$data['Ticket']['title']}";
			$link = array(
				'controller' => 'tickets', 'action' => 'view', $data['Ticket']['number'],
				'#' => 'c'.$data['Comment']['id']
			);
			$pubDate = $data['Comment']['created'];
			$description = null;

			if (!empty($data['Comment']['changes'])) {
				$description .= $chaw->changes($data['Comment']['changes']);
			}
			$description .= $text->truncate(nl2br($data['Comment']['body']), 200, array(
				'exact' => true, 'html' => false
			));
			$author = $data['User']['username'];

			if (!empty($data['Ticket']['Project'])) {
				$data['Project'] = $data['Ticket']['Project'];
			}
		break;

	}

	if (!empty($data['Project']) && $data['Project']['id'] !== $CurrentProject->id) {
		$link = $chaw->url($data['Project'], $link);
		$title = $data['Project']['name'].'/' . $title;
	}

	$pubDate = date('r', strtotime($pubDate));

	echo $rss->item(null, compact('title', 'link', 'pubDate', 'description', 'author'));
}