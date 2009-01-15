<?php 
/* SVN FILE: $Id$ */
/* SourceController Test cases generated on: 2008-09-09 13:09:51 : 1220982111*/
App::import('Controller', 'Source');

class TestSource extends SourceController {
	var $autoRender = false;
}

class SourceControllerTest extends CakeTestCase {
	var $Source = null;
	
	var $fixtures = array(
		'app.project', 'app.permission', 'app.user', 'app.wiki',
		'app.timeline', 'app.comment', 'app.ticket', 'app.version',
		'app.tag', 'app.tags_tickets', 'app.commit'
	);

	function startTest() {
		$this->Source = new TestSource();
		$this->Source->constructClasses();
	}

	function testSourceControllerInstance() {
		$this->assertTrue(is_a($this->Source, 'SourceController'));
	}

	function endTest() {
		unset($this->Source);
	}
}
?>