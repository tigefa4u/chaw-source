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
	var $fixtures = array('app.project', 'app.permission', 'app.user');

	function start() {
		parent::start();
		Configure::write('Content', array(
			'git' => TMP . 'tests' . DS . 'git' . DS,
			'svn' => TMP . 'tests' . DS . 'svn' . DS ,
		));
		$this->Project = new TestProject();
	}

	function testProjectInstance() {
		$this->assertTrue(is_a($this->Project, 'Project'));
	}

	function testProjectSave() {
		$data = array('Project' =>array(
			'name' => 'original project',
			'repo_type' => 'Git',
			'private' => 0,
			'groups' => 'user, docs team, developer, admin',
			'ticket_types' => 'rfc, bug, enhancement',
			'ticket_statuses' => 'open, fixed, invalid, needmoreinfo, wontfix',
			'ticket_priorities' => 'low, normal, high',
			'active' => 1
		));

		$this->assertTrue($this->Project->save($data));
		$path = Configure::read('Content.git');
		$this->assertTrue(file_exists($path . 'repo' . DS . 'permissions.ini'));
		$this->assertFalse(file_exists($this->Project->config['repo']['path'] . DS . 'permissions.ini'));
		@unlink($path . 'permissions.ini');
		@unlink($this->Project->config['repo']['path'] . DS . 'permissions.ini');

		$data = array('Project' =>array(
			'id' => 2,
			'name' => 'test project',
			'repo_type' => 'Git',
			'private' => 0,
			'groups' => 'user, docs team, developer, admin',
			'ticket_types' => 'rfc, bug, enhancement',
			'ticket_statuses' => 'open, fixed, invalid, needmoreinfo, wontfix',
			'ticket_priorities' => 'low, normal, high',
			'active' => 1
		));

		$this->assertTrue($this->Project->save($data));
		$this->assertTrue(file_exists($this->Project->config['repo']['path'] . DS . 'permissions.ini'));
		@unlink($this->Project->config['repo']['path'] . DS . 'permissions.ini');
	}

	function testProjectFind() {

	}
}
?>