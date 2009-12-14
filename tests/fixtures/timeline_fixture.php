<?php
/**
 * Chaw : source code and project management
 *
 * @copyright  Copyright 2009, Garrett J. Woodworth (gwoohoo@gmail.com)
 * @license    GNU AFFERO GENERAL PUBLIC LICENSE v3 (http://opensource.org/licenses/agpl-v3.html)
 *
 */

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