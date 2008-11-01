<?php 
/* SVN FILE: $Id$ */
/* Project Fixture generated on: 2008-10-06 23:10:38 : 1223348498*/

class ProjectFixture extends CakeTestFixture {
	var $name = 'Project';
	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'url' => array('type' => 'string', 'null' => false, 'length' => 200),
		'name' => array('type' => 'string', 'null' => false, 'length' => 200),
		'approved' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'private' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'repo_type' => array('type' => 'string', 'null' => false, 'default' => 'git', 'length' => 10),
		'groups' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'ticket_types' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'ticket_statuses' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'ticket_priorities' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
}
?>