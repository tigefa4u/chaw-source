<?php 
/* SVN FILE: $Id$ */
/* RepoController Test cases generated on: 2009-01-09 16:01:50 : 1231547090*/
App::import('Controller', 'Repo');

class TestRepo extends RepoController {
	var $autoRender = false;
}

class RepoControllerTest extends CakeTestCase {
	var $Repo = null;

	function setUp() {
		$this->Repo = new TestRepo();
		$this->Repo->constructClasses();
	}

	function testRepoControllerInstance() {
		$this->assertTrue(is_a($this->Repo, 'RepoController'));
	}

	function tearDown() {
		unset($this->Repo);
	}
}
?>