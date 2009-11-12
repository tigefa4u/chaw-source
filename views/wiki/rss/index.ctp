<?php
Configure::write('debug', 0);
$description = null;
if(!empty($wiki) && !empty($page)):
	$description = h(nl2br($page['Wiki']['content']));
endif;

$rssFeed = array_filter(explode('/', $path));
$rssFeed['action'] = 'index';
$rssFeed[] = $slug;

$this->set('channel', array(
	'title' => $CurrentProject->name . '/' . $title_for_layout,
	'link' => $rssFeed,
	'description' => $description
));

if(!empty($wiki)):

	foreach($wiki as $page):
		$title = ltrim($page['Wiki']['path'] . '/' . $page['Wiki']['slug'], '/');
		$link = array('controller' => 'wiki', 'action' => 'index', $page['Wiki']['path'], $page['Wiki']['slug']);
		$pubDate = $rss->time($page['Wiki']['modified']);
		$author = $page['User']['username'];
		$description = $text->truncate(nl2br($page['Wiki']['content']), 400, array(
			'exact' => true, 'html' => false
		));
		echo $rss->item(array(), compact('title', 'link', 'pubDate', 'description', 'author'));
	endforeach;

elseif(!empty($page)):

	$title = Inflector::humanize($page['Wiki']['slug']);
	$link = array('controller' => 'wiki', 'action' => 'index', $page['Wiki']['path'], $page['Wiki']['slug']);
	$pubDate = $rss->time($page['Wiki']['modified']);
	$author = $page['User']['username'];
	$description = $text->truncate(nl2br($page['Wiki']['content']), 420, array(
		'exact' => true, 'html' => false
	));

	echo $rss->item(array(), compact('title', 'link', 'pubDate', 'description', 'author'));

endif;
?>