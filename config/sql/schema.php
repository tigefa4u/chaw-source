<?php 
/* SVN FILE: $Id$ */
/* Creampuff schema generated on: 2008-09-09 13:09:31 : 1220981071*/
class CreampuffSchema extends CakeSchema {
	var $name = 'Creampuff';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $tickets = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'type_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'feature_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'owner' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'reporter' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'summary' => array('type' => 'string', 'null' => false, 'length' => 200),
			'body' => array('type' => 'text', 'null' => true, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'status' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 2),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
	var $comments = array(
			'id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'primary'),
			'ticket_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'body' => array('type' => 'text', 'null' => true, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
	var $configs = array(
			'id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'primary'),
			'key' => array('type' => 'string', 'null' => false, 'length' => 50),
			'value' => array('type' => 'string', 'null' => false),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
	var $features = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'milestone_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'title' => array('type' => 'string', 'null' => false, 'length' => 200),
			'points' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 2),
			'difficulty' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 2),
			'story' => array('type' => 'text', 'null' => true, 'default' => NULL),
			'completed' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
	var $milestones = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'title' => array('type' => 'string', 'null' => false, 'length' => 200),
			'due_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'completed' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
	var $sprints = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'feature_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'title' => array('type' => 'string', 'null' => false, 'length' => 200),
			'completed' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
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
	var $tags_features = array(
			'tag_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'feature_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'indexes' => array()
		);
	var $tags_tasks = array(
			'tag_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'task_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'indexes' => array()
		);	
	var $tasks = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'sprint_id' => array('type' => 'integer', 'null' => false),
			'title' => array('type' => 'string', 'null' => false),
			'completed' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
	var $users = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'username' => array('type' => 'string', 'null' => false, 'length' => 40),
			'password' => array('type' => 'string', 'null' => false, 'length' => 40),
			'role' => array('type' => 'string', 'null' => false, 'default' => 'Chicken', 'length' => 100),
			'email' => array('type' => 'string', 'null' => false, 'length' => 200),
			'last_login' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
	var $wiki = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'slug' => array('type' => 'string', 'null' => false, 'length' => 200),
			'raw' => array('type' => 'text', 'null' => true, 'default' => NULL),
			'transformed' => array('type' => 'text', 'null' => true, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
}
?>