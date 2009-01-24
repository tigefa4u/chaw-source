<?php
/* SVN FILE: $Id$ */
/* Chaw schema generated on: 2008-11-22 14:11:16 : 1227383956*/
class ChawSchema extends CakeSchema {
	var $name = 'Chaw';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {

	}

	var $branches = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'project_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 200),
			'ref' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 200),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
	var $comments = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'ticket_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'body' => array('type' => 'text', 'null' => true, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
	var $commits = array(
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
	var $permissions = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'project_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'group' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
	var $projects = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'url' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200),
			'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'fork' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 200),
			'project_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200),
			'approved' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
			'active' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
			'private' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
			'repo_type' => array('type' => 'string', 'null' => false, 'default' => 'git', 'length' => 10),
			'users_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'ohloh_project' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 200),
			'groups' => array('type' => 'string', 'null' => true, 'default' => NULL),
			'ticket_types' => array('type' => 'string', 'null' => true, 'default' => NULL),
			'ticket_statuses' => array('type' => 'string', 'null' => true, 'default' => NULL),
			'ticket_priorities' => array('type' => 'string', 'null' => true, 'default' => NULL),
			'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
			'created' => array('type' => 'date', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'date', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
	var $queues = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'project_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'name' => array('type' => 'string', 'null' => true, 'default' => NULL),
			'slug' => array('type' => 'string', 'null' => true, 'default' => NULL),
			'tickets_count' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
	var $tags = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
			'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
	var $tags_tickets = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'tag_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'ticket_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
	var $tickets = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'number' => array('type' => 'integer', 'null' => false, 'default' => NULL),
			'project_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'version_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'reporter' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'owner' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
			'status' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
			'priority' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
			'title' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200),
			'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
	var $timeline = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'project_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
			'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
	var $users = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'active' => array('type' => 'boolean', 'null' => false, 'default' => 0),
			'username' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 40),
			'password' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 40),
			'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200),
			'ohloh_account' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 200),
			'last_login' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'tmp_pass' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 40),
			'token' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 40),
			'token_expires' => array('type' => 'date', 'null' => true, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
	var $versions = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'project_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'slug' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200),
			'title' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
			'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
			'due_date' => array('type' => 'date', 'null' => true, 'default' => NULL),
			'completed' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
	var $wiki = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'project_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'slug' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200),
			'path' => array('type' => 'string', 'null' => false, 'default' => '/', 'length' => 200),
			'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
			'read_only' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
			'last_changed_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'content' => array('type' => 'text', 'null' => true, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
}
?>