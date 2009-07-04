<?php 
/* SVN FILE: $Id$ */
/* UsersController Test cases generated on: 2008-10-16 10:10:31 : 1224177031*/
App::import('Controller', 'Users');

class TestUsers extends UsersController {
	var $autoRender = false;
}

class UsersControllerTest extends CakeTestCase {
	var $Users = null;
	var $fixtures = array(
		'app.project', 'app.permission', 'app.user', 'app.wiki',
		'app.timeline', 'app.comment', 'app.ticket', 'app.version',
		'app.tag', 'app.tags_tickets', 'app.commit'
	);

	function startTest() {
		$this->Users = new TestUsers();
		$this->Users->constructClasses();
	}

	function testUsersControllerInstance() {
		$this->assertTrue(is_a($this->Users, 'UsersController'));
	}

	function endTests() {
		unset($this->Users);
	}
}
?>