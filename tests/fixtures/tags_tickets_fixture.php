<?php
/**
 * Chaw : source code and project management
 *
 * @copyright  Copyright 2009, Garrett J. Woodworth (gwoohoo@gmail.com)
 * @license    GNU AFFERO GENERAL PUBLIC LICENSE v3 (http://opensource.org/licenses/agpl-v3.html)
 *
 */

class TagsTicketsFixture extends CakeTestFixture {
	var $name = 'TagsTickets';
	var $fields = array(
		'tag_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'primary'),
		'ticket_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array()
	);
}
?>