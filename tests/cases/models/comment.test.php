<?php
/**
 * Chaw : source code and project management
 *
 * @copyright  Copyright 2009, Garrett J. Woodworth (gwoohoo@gmail.com)
 * @license    GNU AFFERO GENERAL PUBLIC LICENSE v3 (http://opensource.org/licenses/agpl-v3.html)
 *
 */

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