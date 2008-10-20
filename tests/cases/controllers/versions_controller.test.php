<?php 
/* SVN FILE: $Id$ */
/* VersionsController Test cases generated on: 2008-10-17 09:10:22 : 1224259342*/
App::import('Controller', 'Versions');

class TestVersions extends VersionsController {
	var $autoRender = false;
}

class VersionsControllerTest extends CakeTestCase {
	var $Versions = null;

	function setUp() {
		$this->Versions = new TestVersions();
		$this->Versions->constructClasses();
	}

	function testVersionsControllerInstance() {
		$this->assertTrue(is_a($this->Versions, 'VersionsController'));
	}

	function tearDown() {
		unset($this->Versions);
	}
}
?>