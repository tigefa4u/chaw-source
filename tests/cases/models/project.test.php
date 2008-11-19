<?php
/* SVN FILE: $Id$ */
/* Project Test cases generated on: 2008-10-06 15:10:20 : 1223321240*/
App::import('Model', 'Project');

class TestProject extends Project {
	var $cacheSources = false;
	var $useDbConfig  = 'test_suite';
}

class ProjectTestCase extends CakeTestCase {
	var $Project = null;
	var $fixtures = array(
		'app.project', 'app.permission', 'app.user', 'app.wiki',
		'app.timeline', 'app.comment', 'app.ticket', 'app.version',
		'app.tag', 'app.tags_tickets', 'app.commit'
	);

	function start() {
		parent::start();
		Configure::write('Content', array(
			'base' => TMP . 'tests' . DS,
			'git' => TMP . 'tests' . DS . 'git' . DS,
			'svn' => TMP . 'tests' . DS . 'svn' . DS ,
		));
		Configure::write('Project', array(
			'id' => 0
		));
		$this->Project = new TestProject();
	}

	function end() {
		parent::end();
		$Cleanup = new Folder(TMP . 'tests/git');
		if ($Cleanup->pwd() == TMP . 'tests/git') {
			$Cleanup->delete();
		}

		$MoreCleanup = new Folder(TMP . 'tests/svn');
		if ($MoreCleanup->pwd() == TMP . 'tests/svn') {
			$MoreCleanup->delete();
		}
	}

	function testProjectInstance() {
		$this->assertTrue(is_a($this->Project, 'Project'));
	}

	function testProjectSave() {
		$data = array('Project' =>array(
			'id' => 1,
			'name' => 'original project',
			'user_id' => 1,
			'username' => 'gwoo',
			'repo_type' => 'Git',
			'private' => 0,
			'groups' => 'user, docs team, developer, admin',
			'ticket_types' => 'rfc, bug, enhancement',
			'ticket_statuses' => 'open, fixed, invalid, needmoreinfo, wontfix',
			'ticket_priorities' => 'low, normal, high',
			'description' => 'this is a test project',
			'active' => 1,
			'approved' => 1,
			'remote' => 'git@git.chaw'
		));

		$this->assertTrue($this->Project->save($data));
		$path = Configure::read('Content.base');
		$this->assertTrue(file_exists($path . 'permissions.ini'));
		$this->assertFalse(file_exists($this->Project->Repo->path . DS . 'permissions.ini'));

		$data = array('Project' =>array(
			'id' => 2,
			'name' => 'test project',
			'username' => 'gwoo',
			'user_id' => 1,
			'repo_type' => 'Git',
			'private' => 0,
			'groups' => 'user, docs team, developer, admin',
			'ticket_types' => 'rfc, bug, enhancement',
			'ticket_statuses' => 'open, fixed, invalid, needmoreinfo, wontfix',
			'ticket_priorities' => 'low, normal, high',
			'description' => 'this is a test project',
			'active' => 1,
			'approved' => 1,
			'remote' => 'git@git.chaw'
		));

		$this->assertTrue($this->Project->save($data));
		$this->assertTrue(file_exists($this->Project->Repo->path . DS . 'permissions.ini'));


		$data = array('Project' =>array(
			'id' => 3,
			'name' => 'svn project',
			'username' => 'gwoo',
			'user_id' => 1,
			'repo_type' => 'Svn',
			'private' => 0,
			'groups' => 'user, docs team, developer, admin',
			'ticket_types' => 'rfc, bug, enhancement',
			'ticket_statuses' => 'open, fixed, invalid, needmoreinfo, wontfix',
			'ticket_priorities' => 'low, normal, high',
			'description' => 'this is a test project',
			'active' => 1,
			'approved' => 1,
			'remote' => 'git@git.chaw'
		));

		$this->assertTrue($this->Project->save($data));
		$this->assertTrue(file_exists($this->Project->Repo->path . DS . 'permissions.ini'));

		$Timeline = ClassRegistry::init('Timeline');
		$result = $Timeline->find('all', array('conditions' => array('Timeline.project_id' => 3)));

		$this->assertEqual($result[0]['Wiki']['slug'], 'home');

	}

	function testProjectFork() {
		//first we have to save a project
		$data = array('Project' =>array(
			'id' => 1,
			'name' => 'original project',
			'user_id' => 1,
			'username' => 'gwoo',
			'repo_type' => 'Git',
			'private' => 0,
			'groups' => 'user, docs team, developer, admin',
			'ticket_types' => 'rfc, bug, enhancement',
			'ticket_statuses' => 'open, fixed, invalid, needmoreinfo, wontfix',
			'ticket_priorities' => 'low, normal, high',
			'description' => 'this is a test project',
			'active' => 1,
			'approved' => 1,
			'remote' => 'git@git.chaw'
		));

		$this->assertTrue($this->Project->save($data));
		$path = Configure::read('Content.base');
		$this->assertTrue(file_exists($path . 'permissions.ini'));
		$this->assertFalse(file_exists($this->Project->Repo->path . DS . 'permissions.ini'));

		$result = file_get_contents($path . 'permissions.ini');
		$expected = "[admin]\ngwoo = crud\n\n[refs/heads/master]\ngwoo = rw";
		$this->assertEqual($result, $expected);

		$this->Project->create(array(
			'project_id' => 1,
			'user_id' => 2,
			'fork' => 'gwoo',
			'approved' => 1
		));
		$this->assertTrue($this->Project->fork($this->Project->config));
		$this->assertTrue(file_exists($this->Project->Repo->path . DS . 'permissions.ini'));

		//pr($this->Project->Repo->debug);
		//pr($this->Project->Repo->response);
	}

	function testProjectActivate() {
		$data = array('Project' =>array(
			'id' => 1,
			'name' => 'original project',
			'user_id' => 1,
			'username' => 'gwoo',
			'repo_type' => 'Git',
			'private' => 0,
			'groups' => 'user, docs team, developer, admin',
			'ticket_types' => 'rfc, bug, enhancement',
			'ticket_statuses' => 'open, fixed, invalid, needmoreinfo, wontfix',
			'ticket_priorities' => 'low, normal, high',
			'description' => 'this is a test project',
			'active' => 1,
			'approved' => 1,
			'remote' => 'git@git.chaw'
		));

		$this->assertTrue($this->Project->save($data));

		$this->Project->id = 1;
		$result = $this->Project->save(array('active' => 1));
		$this->assertTrue($result);

		$data = array('Project' =>array(
			'id' => 2,
			'name' => 'another project',
			'user_id' => 1,
			'username' => 'gwoo',
			'repo_type' => 'Git',
			'private' => 0,
			'groups' => 'user, docs team, developer, admin',
			'ticket_types' => 'rfc, bug, enhancement',
			'ticket_statuses' => 'open, fixed, invalid, needmoreinfo, wontfix',
			'ticket_priorities' => 'low, normal, high',
			'description' => 'this is a test project',
			'active' => 0,
			'approved' => 1,
			'remote' => 'git@git.chaw'
		));

		$this->assertTrue($this->Project->save($data));

		$this->Project->id = 2;
		$result = $this->Project->save(array('active' => 1));
		$this->assertTrue($result);
	}
}
?>