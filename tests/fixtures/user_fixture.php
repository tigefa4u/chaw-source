<?php
/**
 * Chaw : source code and project management
 *
 * @copyright  Copyright 2009, Garrett J. Woodworth (gwoohoo@gmail.com)
 * @license    GNU AFFERO GENERAL PUBLIC LICENSE v3 (http://opensource.org/licenses/agpl-v3.html)
 *
 */

class UserFixture extends CakeTestFixture {
	var $name = 'User';
	var $fields = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'active' => array('type' => 'boolean', 'null' => false, 'default' => 0),
			'username' => array('type' => 'string', 'null' => false, 'length' => 40),
			'password' => array('type' => 'string', 'null' => false, 'length' => 40),
			'email' => array('type' => 'string', 'null' => false, 'length' => 200),
			'ohloh_account' => array('type' => 'string', 'null' => true, 'length' => 200),
			'last_login' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'tmp_pass' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 40),
			'token' => array('type' => 'string', 'null' => true, 'length' => 40),
			'token_expires' => array('type' => 'date', 'null' => true),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
}
?>