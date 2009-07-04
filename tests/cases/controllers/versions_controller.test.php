<?php 
/* SVN FILE: $Id$ */
/* VersionsController Test cases generated on: 2008-10-17 09:10:22 : 1224259342*/
App::import('Controller', 'Versions');

class TestVersions extends VersionsController {
	var $autoRender = false;
}

class VersionsControllerTest extends CakeTestCase {
	var $Versions = null;
	var $fixtures = array(
		'app.project', 'app.permission', 'app.user', 'app.wiki',
		'app.timeline', 'app.comment', 'app.ticket', 'app.version',
		'app.tag', 'app.tags_tickets', 'app.commit'
	);

	function startTest() {
		$this->Versions = new TestVersions();
		$this->Versions->constructClasses();
	}

	function testVersionsControllerInstance() {
		$this->assertTrue(is_a($this->Versions, 'VersionsController'));
	}

	function endTest() {
		unset($this->Versions);
	}
}
?>