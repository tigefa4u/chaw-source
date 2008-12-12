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
define('CHAW_CONTENT', DS . 'htdocs' . DS . 'chaw_content' . DS);

Configure::write('Content', array(
	'base' => CHAW_CONTENT,
	'git' => CHAW_CONTENT . 'git' . DS,
	'svn' => CHAW_CONTENT . 'svn' . DS ,
));

Configure::write('Project', array(
	'id' => null,
	'user_id' => 1,
	'name' => Inflector::humanize(Configure::read('App.dir')),
	'url' => null,
	'repo_type' => 'Git',
	'private' => 0,
	'groups' => 'user, docs team, developer, admin',
	'ticket_types' => 'rfc, bug, enhancement',
	'ticket_statuses' => 'open, fixed, invalid, needmoreinfo, wontfix',
	'ticket_priorities' => 'low, normal, high',
	'active' => 1,
	'remote' => array(
		'git' => 'git@thechaw.com',
		'svn' => 'svn+ssh://svn@thechaw.com'
	)
));
?>