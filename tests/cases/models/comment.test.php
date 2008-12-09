<?php
/* SVN FILE: $Id$ */
/* Comment Test cases generated on: 2008-10-16 22:10:35 : 1224221135*/

class CommentTestCase extends CakeTestCase {
	var $Comment = null;
	var $fixtures = array(
		'app.project', 'app.permission', 'app.user', 'app.wiki',
		'app.timeline', 'app.comment', 'app.ticket', 'app.version',
		'app.tag', 'app.tags_tickets', 'app.commit',
		'app.comment'
	);

	function startTest() {
		$this->Comment =& ClassRegistry::init('Comment');
	}

	function testCommentInstance() {
		$this->assertTrue(is_a($this->Comment, 'Comment'));
	}

	function testCommentFind() {

	}
}
?>