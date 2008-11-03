<?php 
/* SVN FILE: $Id$ */
/* Version Fixture generated on: 2008-10-10 16:10:08 : 1223680508*/

class VersionFixture extends CakeTestFixture {
	var $name = 'Version';
	var $fields = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'title' => array('type' => 'string', 'null' => false, 'length' => 100),
			'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
			'due_date' => array('type' => 'date', 'null' => true, 'default' => NULL),
			'completed' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
}
?>