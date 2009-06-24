<?php
/* SVN FILE: $Id$ */
/* Timeline Fixture generated on: 2008-10-13 09:10:08 : 1223915168*/

class TimelineFixture extends CakeTestFixture {
	var $name = 'Timeline';
	
	var $table = 'timeline';
	
	var $fields = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'project_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
			'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'event' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
			'data' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 200),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
}
?>