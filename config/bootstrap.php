<?php
/**
 * Chaw : source code and project management
 *
 * @copyright  Copyright 2009, Garrett J. Woodworth (gwoohoo@gmail.com)
 * @license    GNU AFFERO GENERAL PUBLIC LICENSE v3 (http://opensource.org/licenses/agpl-v3.html)
 *
 */

$content = APP;

Configure::write('Content', array(
	'base' => $content . 'content' . DS,
	'git' => $content . 'content' . DS . 'git' . DS,
	'svn' => $content . 'content' . DS . 'svn' . DS ,
));

Configure::write('Project', array(
	'id' => null,
	'user_id' => 1,
	'private' => 0,
	'active' => 1,
	'url' => null,
	'name' => Inflector::humanize(Configure::read('App.dir')),
	'repo_type' => 'Git',
	'config' => array(
		'groups' => 'user, docs, team, admin',
		'ticket' => array(
			'types' => 'rfc, bug, enhancement',
			'statuses' => 'pending, approved, in progress, on hold, closed',
			'priorities' => 'low, normal, high',
			'resolutions' => 'fixed, invalid, works-for-me, duplicate, wont-fix'
		)
	),
	'remote' => array(
		'git' => 'git@thechaw.com',
		'svn' => 'svn+ssh://svn@thechaw.com'
	)
));
?>