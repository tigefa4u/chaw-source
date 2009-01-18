<?php
/* SVN FILE: $Id$ */
/* Commit Fixture generated on: 2008-10-16 22:10:35 : 1224221135*/

class CommitFixture extends CakeTestFixture {
	var $name = 'Commit';
	var $fields = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'project_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'branch_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'branch' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 200),
			'revision' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 40),
			'author' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 200),
			'commit_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'message' => array('type' => 'string', 'null' => true, 'default' => NULL),
			'changes' => array('type' => 'text', 'null' => true, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
}
?>