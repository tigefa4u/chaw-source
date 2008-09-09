<?php 
/* SVN FILE: $Id$ */
/* SvnController Test cases generated on: 2008-03-11 20:03:05 : 1205293445*/
App::import('Controller', 'Svn');

class TestSvn extends SvnController {
	var $autoRender = false;
}

class SvnControllerTest extends CakeTestCase {
	var $Svn = null;

	function setUp() {
		$this->Svn = new TestSvn();
	}

	function testSvnControllerInstance() {
		$this->assertTrue(is_a($this->Svn, 'SvnController'));
	}

	function tearDown() {
		unset($this->Svn);
	}
}
?>