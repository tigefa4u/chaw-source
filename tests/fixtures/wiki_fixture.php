<?php
/**
 * Chaw : source code and project management
 *
 * @copyright  Copyright 2009, Garrett J. Woodworth (gwoohoo@gmail.com)
 * @license    GNU AFFERO GENERAL PUBLIC LICENSE v3 (http://opensource.org/licenses/agpl-v3.html)
 *
 */

class WikiFixture extends CakeTestFixture {
	var $name = 'Wiki';
	var $table = 'wiki';

	var $fields = array(
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