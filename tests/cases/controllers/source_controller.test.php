<?php
/* SVN FILE: $Id$ */
/* SourceController Test cases generated on: 2008-09-09 13:09:51 : 1220982111*/
App::import('Controller', 'Source');
App::import('Model', 'Project');


class TestSource extends SourceController {
	var $autoRender = false;

	function render() {
		return $this->viewVars;
	}
}

class TestSourceProject extends Project {

	function branches() {
		return array(
			array('Branch' => array(
				'name' => 'master'
			)),
			array('Branch' => array(
				'name' => 'new'
			))
		);
	}
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

		Configure::write('Content.git', TMP . 'tests/git/');
		$this->Git =& ClassRegistry::init(array(
			'class' => 'Repo.Git',
			'type' => 'git',
			'path' => TMP . 'tests/git/repo/test.git',
			'working' => TMP . 'tests/git/working/test',
			'chmod' => 0777
		));

		$this->assertTrue($this->Git->create());

		$this->Git->logResponse = true;

		$this->Git->fork('gwoo');
		$Folder = new Folder(TMP . 'tests/git/working/forks/gwoo/test/master/folder', true);
		$File = new File(TMP . 'tests/git/working/forks/gwoo/test/master/folder/file.txt', true);

		$this->Git->commit('this is a new message');
		$this->Git->push();

		$this->Git->config(array(
			'path' => TMP . 'tests/git/repo/test.git',
			'working' => TMP . 'tests/git/working/test',
		));

		$this->Git->merge('test', 'gwoo');
		$this->assertTrue(file_exists(TMP . 'tests/git/working/test/master/folder'));

		$data = $this->Git->read();
		$this->assertEqual($data['message'], 'Merge from forks/gwoo/test.git');

		$this->Source->Project = new TestSourceProject();

		$this->Git->branch = null;
		$this->Git->config(array(
			'path' => TMP . 'tests/git/repo/test.git',
			'working' => TMP . 'tests/git/working/test',
		));
		$this->Source->Project->Repo = $this->Git;


		$this->Svn = ClassRegistry::init(array(
			'class' => 'Repo.Svn',
			'type' => 'svn',
			'path' => TMP . 'tests/svn/repo/test',
			'working' => TMP . 'tests/svn/working/test',
			'chmod' => 0777
		));

	}

	function endTest() {
		$Cleanup = new Folder(TMP . 'tests/git');
		if ($Cleanup->pwd() == TMP . 'tests/git') {
			$Cleanup->delete();
		}
		unset($this->Source);
	}

	function igetTests() {
		return array('start', 'testGitBranchesWithArgs', 'end');
	}

	function testSourceControllerInstance() {
		$this->assertTrue(is_a($this->Source, 'SourceController'));
	}


	function testIndex() {
		$this->Source->Project->Repo->branch = null;
		$this->Source->index();
		$data = $this->Source->viewVars['data'];
		$this->assertEqual($data['Folder'][0]['name'], 'folder');
		$this->assertEqual($data['Folder'][0]['info']['message'], 'Merge from forks/gwoo/test.git');
		unset($this->Source->viewVars['data']);

		$this->assertEqual($this->Source->viewVars, array(
			'path' => null,
			'args' => array('branches'),
			'current' => 'master',
		));
	}

	function testSvnBranches() {
		$this->Source->Project->Repo = $this->Svn;
		$this->Source->branches();

		$data = $this->Source->viewVars['data'];

		$this->assertEqual($data['Folder'][0]['name'], null);
		unset($this->Source->viewVars['data']);

		$this->assertEqual($this->Source->viewVars, array(
			'path' => 'branches',
			'args' => array(),
			'current' => 'branches',
			'branch' => null
		));

	}

	function testGitBranches() {
		$this->Source->branches();

		$data = $this->Source->viewVars['data'];
		$this->assertEqual($data['Folder'][0]['name'], 'master');
		$this->assertEqual($data['Folder'][0]['info']['message'], 'Merge from forks/gwoo/test.git');
		unset($this->Source->viewVars['data']);

		$this->assertEqual($this->Source->viewVars, array(
			'path' => null,
			'args' => array(),
			'current' => 'branches',
			'branch' => null
		));
	}

	function testGitBranchesWithArgs() {
		$this->Source->Project->Repo->branch('new', true);
		$this->Source->Project->Repo->cd();
		$this->Source->Project->Repo->checkout(array('-b', 'new'));
		$this->Source->Project->Repo->push('origin', 'new');

		$this->Source->Project->Repo->branch = null;

		$this->Source->branches('new');

		$data = $this->Source->viewVars['data'];
		$this->assertEqual($data['Folder'][0]['name'], 'folder');
		$this->assertEqual($data['Folder'][0]['path'], 'branches/new/folder');
		$this->assertEqual($data['Folder'][0]['info']['message'], 'Merge from forks/gwoo/test.git');
		unset($this->Source->viewVars['data']);

		$this->assertEqual($this->Source->viewVars, array(
			'path' => '',
			'args' => array('branches'),
			'current' => 'new',
			'branch' => 'new'
		));

		$this->Source->Project->Repo->branch = null;
		$this->Source->branches('new', 'test.txt');

		$data = $this->Source->viewVars['data'];
		$this->assertEqual($data['Folder'], array());
		unset($this->Source->viewVars['data']);

		$this->assertEqual($this->Source->viewVars, array(
			'path' => 'test.txt',
			'args' => array('branches', 'new'),
			'current' => 'test.txt',
			'branch' => 'new'
		));

		$this->assertEqual($data['Folder'], array());

		//pr($this->Git->debug);
		//die();
	}
}
?>