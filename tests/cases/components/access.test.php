<?php
/* SVN FILE: $Id$ */
/* Access Test cases generated on: 2008-11-01 10:11:29 : 1225561949*/
App::import('Component', array('Auth', 'Session', 'Access'));
App::import('Controller');
App::import('Model', array('Project', 'Permission'));

class TestAccess extends AccessComponent {
}

class TestProject extends Project {
	var $cacheSources = false;
	var $useDbConfig  = 'test_suite';
}

class TestAccessController extends Controller {

	var $components = array('Access', 'Auth');

	var $testRedirect = null;

	function redirect($url, $status = false) {
		return $this->testRedirect = $url;
	}
}

class AccessComponentTest extends CakeTestCase {

	var $fixtures = array(
		'app.project', 'app.permission', 'app.user', 'app.wiki',
		'app.timeline', 'app.comment', 'app.ticket', 'app.version',
		'app.tag', 'app.tags_tickets', 'app.commit'
	);

	function testUser() {
		$Access = new TestAccess();

		$Access->user = array('User' => array(
			'id' => 1, 'username' => 'gwoo'
		));

		$result = $Access->user();
		$expected = array('id' => 1, 'username' => 'gwoo');
		$this->assertEqual($result, $expected);

		$result = $Access->user('username');
		$expected = 'gwoo';
		$this->assertEqual($result, $expected);
	}

	function __runStartup() {
		$this->Controller->Access->user = array();
		$this->Controller->Auth->allowedActions = array();
		$this->Controller->action = $this->Controller->params['action'];
		$this->Controller->Component->init($this->Controller);
		$this->Controller->Component->initialize($this->Controller);

		$this->Controller->Access->startup($this->Controller);
		if ($this->Controller->testRedirect == null) {
			$this->Controller->Auth->startup($this->Controller);
		}
	}

	function testInstall() {
		$Access = new TestAccess();
		
		$this->Controller->Project = new TestProject();
		
		$this->Controller->params = array(
			'controller' => 'browser',
			'action' => 'index',
			'url' => array('url' => '/')
		);
		$this->__runStartup($this->Controller);
		$expected = array('admin' => false, 'project' => false, 'controller' => 'pages', 'action' => 'start');
		$this->assertEqual($this->Controller->testRedirect, $expected);
		
		$this->Controller->Project = null;
		
		$this->Controller->params = array(
			'controller' => 'pages',
			'action' => 'start',
			'url' => array('url' => 'start')
		);

		$this->Controller->testRedirect = null;
		$this->__runStartup($this->Controller);
		$expected = null;
		$this->assertEqual($this->Controller->testRedirect, $expected);

		$this->Controller->Project = new TestProject();

		$this->Controller->params = array(
			'controller' => 'users',
			'action' => 'add',
			'url' => array('url' => 'users/add')
		);
		$this->Controller->testRedirect = null;
		$this->__runStartup($this->Controller);
		$expected = null;
		$this->assertEqual($this->Controller->testRedirect, $expected);

		$this->Controller->Session->delete('Install');
	}

	function testAccessAfterInstallation() {
		$data = array('Project' =>array(
			'id' => 1,
			'name' => 'original project',
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

		$this->Controller->Project = new TestProject();
		$this->assertTrue($this->Controller->Project->save($data));

		$this->Controller->testRedirect = null;

		$this->Controller->params = array(
			'project' => null,
			'controller' => 'users',
			'action' => 'login',
			'url' => array('url' => 'users/login')
		);
		$this->__runStartup($this->Controller);
		$this->assertNull($this->Controller->testRedirect);

		$this->Controller->testRedirect = null;
		$this->Controller->params = array(
			'project' => null,
			'controller' => 'users',
			'action' => 'logout',
			'url' => array('url' => 'users/logout')
		);
		$this->__runStartup($this->Controller);
		$this->assertNull($this->Controller->testRedirect);

		$this->Controller->testRedirect = null;
		$this->Controller->params = array(
			'project' => null,
			'controller' => 'users',
			'action' => 'account',
			'url' => array('url' => 'users/account')
		);
		$this->__runStartup($this->Controller);

		$result = $this->Controller->testRedirect;
		$expected = '/users/login';
		$this->assertEqual($result, $expected);
	}

	function testOwnerAndinstalled() {
		$data = array('Project' =>array(
			'id' => 1,
			'name' => 'Chaw',
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

		$this->Controller->Project = new TestProject();
		$this->assertTrue($this->Controller->Project->save($data));

		$this->Controller->testRedirect = null;

		$this->Controller->params = array(
			'project' => null,
			'controller' => 'browser',
			'action' => 'index',
			'url' => array('url' => 'source')
		);

		$this->Controller->Session->write('Auth.User', array('id' => 1, 'username' => 'gwoo'));

		$this->__runStartup($this->Controller);
		$this->assertNull($this->Controller->testRedirect);
		$this->assertTrue($this->Controller->params['isAdmin']);

		$this->Controller->Session->del('Auth.User');
	}


	function testAnonymousAndPublic() {
		$data = array('Project' =>array(
			'id' => 1,
			'name' => 'original project',
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

		$this->Controller->Project = new TestProject();
		$this->assertTrue($this->Controller->Project->save($data));

		$this->Controller->testRedirect = null;

		$this->Controller->params = array(
			'project' => null,
			'controller' => 'browser',
			'action' => 'index',
			'url' => array('url' => 'source')
		);

		$this->__runStartup($this->Controller);
		$this->assertNull($this->Controller->testRedirect);

		$this->Controller->testRedirect = null;

		$this->Controller->params = array(
			'project' => 'original_project',
			'controller' => 'projects',
			'action' => 'index',
			'url' => array('url' => 'projects')
		);

		$this->__runStartup($this->Controller);
		$this->assertNull($this->Controller->testRedirect);
	}

	function testAnonymousAndPrivate() {
		$this->Controller->Session->del('Auth.User');

		$data = array('Project' =>array(
			'id' => 1,
			'name' => 'Chaw',
			'user_id' => 1,
			'repo_type' => 'Git',
			'private' => 1,
			'groups' => 'user, docs team, developer, admin',
			'ticket_types' => 'rfc, bug, enhancement',
			'ticket_statuses' => 'open, fixed, invalid, needmoreinfo, wontfix',
			'ticket_priorities' => 'low, normal, high',
			'description' => 'this is a test project',
			'active' => 1,
			'approved' => 1,
			'remote' => 'git@git.chaw'
		));

		$this->Controller->Project = new TestProject();
		$this->assertTrue($this->Controller->Project->save($data));

		$this->Controller->testRedirect = null;

		$this->Controller->params = array(
			'project' => null,
			'controller' => 'browser',
			'action' => 'index',
			'url' => array('url' => 'source')
		);

		$this->__runStartup($this->Controller);
		$expected = array('admin' => false, 'controller' => 'projects');
		$this->assertEqual($this->Controller->testRedirect, $expected);
		$this->assertFalse($this->Controller->params['isAdmin']);

		$this->Controller->testRedirect = null;
		$this->Controller->params = array(
			'project' => null,
			'controller' => 'projects',
			'action' => 'index',
			'url' => array('url' => 'projects')
		);

		$this->__runStartup($this->Controller);
		$this->assertNull($this->Controller->testRedirect);

	}

	function testUserAndPrivate() {
		$this->Controller->Session->del('Auth.User');

		$data = array('Project' =>array(
			'id' => 1,
			'name' => 'Chaw',
			'user_id' => 1,
			'repo_type' => 'Git',
			'private' => 1,
			'groups' => 'user, docs team, developer, admin',
			'ticket_types' => 'rfc, bug, enhancement',
			'ticket_statuses' => 'open, fixed, invalid, needmoreinfo, wontfix',
			'ticket_priorities' => 'low, normal, high',
			'description' => 'this is a test project',
			'active' => 1,
			'approved' => 1,
			'remote' => 'git@git.chaw'
		));

		$this->Controller->Project = new TestProject();
		$this->assertTrue($this->Controller->Project->save($data));

		$this->Controller->testRedirect = null;

		$this->Controller->params = array(
			'project' => null,
			'controller' => 'browser',
			'action' => 'index',
			'url' => array('url' => 'source')
		);

		$this->Controller->Session->write('Auth.User', array('id' => 4, 'username' => 'bob'));

		$this->__runStartup($this->Controller);
		$expected = array('admin' => false, 'controller' => 'projects');
		$this->assertEqual($this->Controller->testRedirect, $expected);
		$this->assertFalse($this->Controller->params['isAdmin']);

		$this->Controller->Session->del('Auth.User');
	}


	function start() {
		parent::start();

		$this->Controller = new TestAccessController();

		Configure::write('Content', array(
			'base' => TMP . 'tests' . DS,
			'git' => TMP . 'tests' . DS . 'git' . DS,
			'svn' => TMP . 'tests' . DS . 'svn' . DS ,
		));
		$this->__projects = array(
			'One' => array(
				'id' => 1,
				'url' => 'chaw',
				'repo' => array(
					'type' => 'git',
					'path' => TMP . 'tests' . DS . 'git' . DS . 'repo' . DS . 'chaw.git',
					'working' => TMP . 'tests' . DS . 'git' . DS . 'working' . DS . 'chaw'
				)
			),
			'Two' => array(
				'id' => 2,
				'url' => 'project_two',
				'repo' => array(
					'type' => 'git',
					'path' => TMP . 'tests' . DS . 'git' . DS . 'repo' . DS . 'project_two.git',
					'working' => TMP . 'tests' . DS . 'git' .DS . 'working' . DS . 'project_two'
				)
			)
		);

		Configure::write('Project', $this->__projects['One']);
		$Permission = new Permission();

		$data['Permission']['fine_grained'] = "
			[wiki]
			* = r

			[tickets]
			* = r
		";

		$Permission->saveFile($data);
	}

	function end() {
		parent::end();
		$Cleanup = new Folder(TMP . 'tests/git');
		if ($Cleanup->pwd() == TMP . 'tests/git') {
			$Cleanup->delete();
		}
	}

}
?>