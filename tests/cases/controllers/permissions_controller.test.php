<?php 
/* SVN FILE: $Id$ */
/* PermissionsController Test cases generated on: 2008-10-16 15:10:08 : 1224196628*/
App::import('Controller', 'Permissions');

class TestPermissions extends PermissionsController {
	var $autoRender = false;
}

class PermissionsControllerTest extends CakeTestCase {
	var $Permissions = null;

	function setUp() {
		$this->Permissions = new TestPermissions();
		$this->Permissions->constructClasses();
	}

	function testPermissionsControllerInstance() {
		$this->assertTrue(is_a($this->Permissions, 'PermissionsController'));
	}

	function tearDown() {
		unset($this->Permissions);
	}
}
?>