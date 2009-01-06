<?php 
/* SVN FILE: $Id$ */
/* CommentsController Test cases generated on: 2009-01-06 13:01:49 : 1231277809*/
App::import('Controller', 'Comments');

class TestComments extends CommentsController {
	var $autoRender = false;
}

class CommentsControllerTest extends CakeTestCase {
	var $Comments = null;

	function setUp() {
		$this->Comments = new TestComments();
		$this->Comments->constructClasses();
	}

	function testCommentsControllerInstance() {
		$this->assertTrue(is_a($this->Comments, 'CommentsController'));
	}

	function tearDown() {
		unset($this->Comments);
	}
}
?>