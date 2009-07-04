<?php 
/* SVN FILE: $Id$ */
/* WikiController Test cases generated on: 2008-08-28 17:08:49 : 1219968829*/
App::import('Controller', 'Wiki');

class TestWiki extends WikiController {
	var $autoRender = false;
}

class WikiControllerTest extends CakeTestCase {
	var $Wiki = null;
	var $fixtures = array(
		'app.project', 'app.permission', 'app.user', 'app.wiki',
		'app.timeline', 'app.comment', 'app.ticket', 'app.version',
		'app.tag', 'app.tags_tickets', 'app.commit'
	);

	function startTest() {
		$this->Wiki = new TestWiki();
		$this->Wiki->constructClasses();
	}

	function testWikiControllerInstance() {
		$this->assertTrue(is_a($this->Wiki, 'WikiController'));
	}

	function endTest() {
		unset($this->Wiki);
	}
}
?>