<?php 
/* SVN FILE: $Id$ */
/* PermissionsController Test cases generated on: 2008-10-16 15:10:08 : 1224196628*/
App::import('Controller', 'Permissions');

class TestPermissions extends PermissionsController {
	var $autoRender = false;
}

class PermissionsControllerTest extends CakeTestCase {
	var $Permissions = null;
	var $fixtures = array(
		'app.project', 'app.permission', 'app.user', 'app.wiki',
		'app.timeline', 'app.comment', 'app.ticket', 'app.version',
		'app.tag', 'app.tags_tickets', 'app.commit'
	);

	function startTest() {
		$this->Permissions = new TestPermissions();
		$this->Permissions->constructClasses();
	}

	function testPermissionsControllerInstance() {
		$this->assertTrue(is_a($this->Permissions, 'PermissionsController'));
	}

	function endTest() {
		unset($this->Permissions);
	}
}
?>