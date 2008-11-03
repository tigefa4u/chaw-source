<?php 
/* SVN FILE: $Id$ */
/* WikiController Test cases generated on: 2008-08-28 17:08:49 : 1219968829*/
App::import('Controller', 'Wiki');

class TestWiki extends WikiController {
	var $autoRender = false;
}

class WikiControllerTest extends CakeTestCase {
	var $Wiki = null;

	function setUp() {
		$this->Wiki = new TestWiki();
		$this->Wiki->constructClasses();
	}

	function testWikiControllerInstance() {
		$this->assertTrue(is_a($this->Wiki, 'WikiController'));
	}

	function tearDown() {
		unset($this->Wiki);
	}
}
?>