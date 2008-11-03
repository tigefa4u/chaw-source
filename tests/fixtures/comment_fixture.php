<?php 
/* SVN FILE: $Id$ */
/* Comment Fixture generated on: 2008-10-16 22:10:35 : 1224221135*/

class CommentFixture extends CakeTestFixture {
	var $name = 'Comment';
	var $fields = array(
			'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'ticket_id' => array('type'=>'integer', 'null' => false, 'default' => '0'),
			'body' => array('type'=>'text', 'null' => true, 'default' => NULL),
			'created' => array('type'=>'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type'=>'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
			);
}
?>