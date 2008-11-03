<?php 
/* SVN FILE: $Id$ */
/* BrowserController Test cases generated on: 2008-09-09 13:09:51 : 1220982111*/
App::import('Controller', 'Browser');

class TestBrowser extends BrowserController {
	var $autoRender = false;
}

class BrowserControllerTest extends CakeTestCase {
	var $Browser = null;

	function setUp() {
		$this->Browser = new TestBrowser();
		$this->Browser->constructClasses();
	}

	function testBrowserControllerInstance() {
		$this->assertTrue(is_a($this->Browser, 'BrowserController'));
	}

	function tearDown() {
		unset($this->Browser);
	}
}
?>