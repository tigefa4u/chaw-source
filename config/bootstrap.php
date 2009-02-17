<?php
/**
 * Short description for file.
 *
 * Long description for file
 *
 *
 * Copyright 2008, Garrett J. Woodworth <gwoo@cakephp.org>
 * Licensed under The MIT License
 * Redistributions of files must retain the copyright notice.
 *
 * @copyright		Copyright 2008, Garrett J. Woodworth
 * @package			chaw
 * @subpackage		chaw.config
 * @since			Chaw 0.1
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 */
Configure::write('Content', array(
	'base' => APP . 'content' . DS,
	'git' => APP . 'content' . DS . 'git' . DS,
	'svn' => APP . 'content' . DS . 'svn' . DS ,
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
			'resolutions' => 'fixed, invalid, worksforme, duplicate, wontfix'
		)
	),
	'remote' => array(
		'git' => 'git@thechaw.com',
		'svn' => 'svn+ssh://svn@thechaw.com'
	)
));
?>