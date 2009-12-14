<?php
/**
 * Chaw : source code and project management
 *
 * @copyright  Copyright 2009, Garrett J. Woodworth (gwoohoo@gmail.com)
 * @license    GNU AFFERO GENERAL PUBLIC LICENSE v3 (http://opensource.org/licenses/agpl-v3.html)
 *
 */

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