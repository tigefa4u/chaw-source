<?php
/**
 * Chaw : source code and project management
 *
 * @copyright  Copyright 2009, Garrett J. Woodworth (gwoohoo@gmail.com)
 * @license    GNU AFFERO GENERAL PUBLIC LICENSE v3 (http://opensource.org/licenses/agpl-v3.html)
 *
 */

class CommitFixture extends CakeTestFixture {
	var $name = 'Commit';
	var $fields = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'project_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'branch' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 200),
			'revision' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 40),
			'author' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 200),
			'committer' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 200),
			'commit_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'message' => array('type' => 'string', 'null' => true, 'default' => NULL),
			'changes' => array('type' => 'text', 'null' => true, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
}
?>