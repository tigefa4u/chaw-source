<?php
/* SVN FILE: $Id$ */
/* User Test cases generated on: 2008-10-16 10:10:23 : 1224177023*/
App::import('Model', 'User');

class UserTestCase extends CakeTestCase {
	var $User = null;
	var $fixtures = array(
		'app.project', 'app.permission', 'app.user', 'app.wiki',
		'app.timeline', 'app.comment', 'app.ticket', 'app.version',
		'app.tag', 'app.tags_tickets', 'app.commit', 'app.branch'
	);

	function start() {
		parent::start();
		Configure::write('Content', array(
			'base' => TMP . 'tests' . DS,
			'git' => TMP . 'tests' . DS . 'git' . DS,
			'svn' => TMP . 'tests' . DS . 'svn' . DS ,
		));

		$this->AuthorizedKeys = new File(TMP . 'tests/git/repo/.ssh/authorized_keys', true);

		$this->User =& ClassRegistry::init('User');
	}

	function end() {
		parent::end();
		$Cleanup = new Folder(TMP . 'tests/git');
		if ($Cleanup->pwd() == TMP . 'tests/git') {
			$Cleanup->delete();
		}
	}

	function testUserInstance() {
		$this->assertTrue(is_a($this->User, 'User'));
	}

	function testUserGroups() {
		$this->__addProject();

		$this->User->create(array('username' => 'gwoo', 'email' => 'gwoo@test.com'));
		$this->assertTrue($this->User->save());

		$this->User->set(array('project_id' => 1, 'group' => 'developer'));
		$this->User->permit();

		$results = $this->User->groups(1);
		$this->assertEqual($results, array(1 => 'developer'));
	}

	function testUseProjects() {
		$this->__addProject();

		$this->User->create(array('username' => 'gwoo', 'email' => 'gwoo@test.com'));
		$this->assertTrue($this->User->save());

		$this->User->set(array('project_id' => 1, 'group' => 'developer'));
		$this->User->permit();

		$results = $this->User->projects(1);
		$this->assertEqual($results['ids'], array(1));
	}

	function testUserValidation() {
		$this->User->create(array('username' => 'gwoo'));
		$this->assertFalse($this->User->save());
		$this->assertEqual($this->User->validationErrors, array('email' => 'Required: Email must be unique'));

		$this->User->create(array('email' => 'gwoo@test.com'));
		$this->assertFalse($this->User->save());
		$this->assertEqual($this->User->validationErrors, array('username' => 'Required: Username must be unique'));
	}

	function testActivate() {
		$this->User->create(array('username' => 'gwoo', 'email' => 'gwoo@test.com'));
		$this->assertTrue($this->User->save());
		$this->assertEqual($this->User->validationErrors, array());

		$results = $this->User->setToken(array('id' => 1));
		$this->assertEqual($results['User']['email'], 'gwoo@test.com');
		$this->assertEqual($this->User->validationErrors, array());

		$results = $this->User->activate($results['User']['token']);
		$this->assertEqual($results['User']['active'], 1);
		$this->assertEqual($results['User']['email'], 'gwoo@test.com');
		$this->assertEqual($this->User->validationErrors, array());

	}

	function testSetToken() {
		$this->User->create(array('username' => 'gwoo', 'email' => 'gwoo@test.com'));
		$this->assertTrue($this->User->save());
		$this->assertEqual($this->User->validationErrors, array());

		$this->User->id = null;
		$this->User->data = array();
		$results = $this->User->setToken(array('username' => 'gwoo'));
		$this->assertEqual($results['User']['email'], 'gwoo@test.com');
		$this->assertEqual($this->User->validationErrors, array());

		$this->User->id = null;
		$this->User->data = array();
		$results = $this->User->setToken(array('email' => 'gwoo@test.com'));
		$this->assertEqual($results['User']['email'], 'gwoo@test.com');
		$this->assertEqual($this->User->validationErrors, array());
	}

	function testSetTempPassword() {
		$this->User->create(array('username' => 'gwoo', 'email' => 'gwoo@test.com'));
		$this->assertTrue($this->User->save());
		$this->assertEqual($this->User->validationErrors, array());

		$results = $this->User->setToken(array('email' => 'gwoo@test.com'));
		$this->assertEqual($results['User']['email'], 'gwoo@test.com');
		$this->assertEqual($this->User->validationErrors, array());


		$results = $this->User->setTempPassword(array('token' => $results['User']['token']));
		$this->assertEqual(strlen($results['User']['tmp_pass']), 10);
		$this->assertEqual($results['User']['username'], 'gwoo');
		$this->assertEqual($results['User']['email'], 'gwoo@test.com');
		$this->assertEqual($this->User->validationErrors, array());
	}

	function __addProject() {
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

		$this->assertTrue($this->User->Permission->Project->save($data));
		$path = Configure::read('Content.base');
		$this->assertTrue(file_exists($path . 'permissions.ini'));
		$this->assertFalse(file_exists($this->User->Permission->Project->Repo->path . DS . 'permissions.ini'));
	}

}
?>