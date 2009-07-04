<?php
/* SVN FILE: $Id$ */
/* RepoController Test cases generated on: 2009-01-09 16:01:50 : 1231547090*/
App::import('Controller', 'Repo');

class TestRepo extends RepoController {
	var $autoRender = false;
}

class RepoControllerTest extends CakeTestCase {
	var $Repo = null;

	var $fixtures = array(
		'app.project', 'app.permission', 'app.user', 'app.wiki',
		'app.timeline', 'app.comment', 'app.ticket', 'app.version',
		'app.tag', 'app.tags_tickets', 'app.commit', 'app.branch'
	);

	function startTest() {

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