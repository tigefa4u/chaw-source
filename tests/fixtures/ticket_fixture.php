<?php
/* SVN FILE: $Id$ */
/* Ticket Fixture generated on: 2008-09-23 07:09:29 : 1222170869*/

class TicketFixture extends CakeTestFixture {
	var $name = 'Ticket';
	var $fields = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'number' => array('type' => 'integer', 'null' => false, 'default' => NULL),
			'project_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'version_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'reporter' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'owner' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
			'status' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
			'resolution' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
			'priority' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
			'title' => array('type' => 'string', 'null' => false, 'length' => 200),
			'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
}
?>