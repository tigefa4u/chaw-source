<?php 
/* SVN FILE: $Id$ */
/* Project Fixture generated on: 2008-10-06 23:10:38 : 1223348498*/

class ProjectFixture extends CakeTestFixture {
	var $name = 'Project';
	var $fields = array(
			'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'name' => array('type'=>'string', 'null' => false, 'length' => 200),
			'description' => array('type'=>'text', 'null' => true, 'default' => NULL),
			'created' => array('type'=>'date', 'null' => true, 'default' => NULL),
			'modified' => array('type'=>'date', 'null' => true, 'default' => NULL),
			'active' => array('type'=>'boolean', 'null' => true, 'default' => NULL),
			'ticket_types' => array('type'=>'string', 'null' => true, 'default' => NULL),
			'groups' => array('type'=>'string', 'null' => true, 'default' => NULL),
			'priorities' => array('type'=>'string', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
			);
}
?>