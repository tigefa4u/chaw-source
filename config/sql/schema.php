<?php 
/* SVN FILE: $Id$ */
/* Chaw schema generated on: 2008-10-11 20:10:41 : 1223783441*/
class ChawSchema extends CakeSchema {
	var $name = 'Chaw';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $comments = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'ticket_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'body' => array('type' => 'text', 'null' => true, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
	var $projects = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'url' => array('type' => 'string', 'null' => false, 'length' => 200),
			'name' => array('type' => 'string', 'null' => false, 'length' => 200),
			'groups' => array('type' => 'string', 'null' => true, 'default' => NULL),
			'ticket_types' => array('type' => 'string', 'null' => true, 'default' => NULL),
			'ticket_statuses' => array('type' => 'string', 'null' => true, 'default' => NULL),
			'ticket_priorities' => array('type' => 'string', 'null' => true, 'default' => NULL),
			'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
			'created' => array('type' => 'date', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'date', 'null' => true, 'default' => NULL),
			'active' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
	var $tags = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'key' => array('type' => 'string', 'null' => false, 'length' => 100),
			'name' => array('type' => 'string', 'null' => false, 'length' => 100),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
	var $tags_tickets = array(
			'tag_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'ticket_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'indexes' => array()
		);
	var $tickets = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'project_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'version_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
			'status' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
			'priority' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
			'owner' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'reporter' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'title' => array('type' => 'string', 'null' => false, 'length' => 200),
			'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
	var $timeline = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'project_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'summary' => array('type' => 'text', 'null' => true, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
	var $users = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'username' => array('type' => 'string', 'null' => false, 'length' => 40),
			'password' => array('type' => 'string', 'null' => false, 'length' => 40),
			'group_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
			'email' => array('type' => 'string', 'null' => false, 'length' => 200),
			'last_login' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
	var $versions = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'title' => array('type' => 'string', 'null' => false, 'length' => 100),
			'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'completed' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
			'due' => array('type' => 'date', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
	var $wiki = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'project_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'slug' => array('type' => 'string', 'null' => false, 'length' => 200),
			'content' => array('type' => 'text', 'null' => true, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
}
?>