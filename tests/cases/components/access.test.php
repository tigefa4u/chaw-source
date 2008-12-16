<?php
/* SVN FILE: $Id$ */
/* Access Test cases generated on: 2008-11-01 10:11:29 : 1225561949*/
App::import('Component', array('Auth', 'Session', 'Access'));
App::import('Controller');
App::import('Model', array('Project', 'Permission'));

class TestAccess extends AccessComponent {
}

class TestAccessProject extends Project {
	var $cacheSources = false;
	var $useDbConfig  = 'test_suite';
	var $useTable = 'projects';
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
		$this->Controller->action = $this->Controller->params['action'];

		$this->Controller->Component->init($this->Controller);
		$this->Controller->Component->initialize($this->Controller);

		$this->Controller->Auth->loginAction = '/users/login';
		$this->Controller->Auth->mapActions(array(
			'modify' => 'update',
			'remove' => 'delete'
		));

		if ($this->Controller->params['controller'] == 'users') {
			$this->Controller->Auth->mapActions(array(
				'account' => 'update', 'change' => 'update'
			));
			$this->Controller->Auth->allow('forgotten', 'verify', 'add', 'login', 'logout');
			$this->Controller->Access->allow('forgotten', 'verify', 'add', 'login', 'logout', 'account', 'edit', 'change');
		}

		if ($this->Controller->params['controller'] == 'projects') {
			$this->Controller->Auth->mapActions(array('fork' => 'create'));
			$this->Controller->Auth->allow('index');
			$this->Controller->Access->allow('index');
		}

		if ($this->Controller->params['controller'] == 'dashboard') {
			$this->Controller->Auth->allow('index');
		}

		$this->Controller->Access->startup($this->Controller);

		if ($this->Controller->testRedirect == null) {
			$this->Controller->Auth->startup($this->Controller);
		}
		$this->Controller->Auth->allowedActions = array();
		$this->Controller->Access->allowedActions = array();
	}

	function testCheckPublicAnonymous() {
		$Access = new TestAccess();
		$this->Controller->Project = ClassRegistry::init('Project');
		$this->Controller->Component->init($this->Controller);
		$this->Controller->Component->initialize($this->Controller);
		$this->Controller->params['controller'] = 'wiki';

		$Access->isPublic = true;
		$Access->user = array();
		$this->assertTrue($Access->check($this->Controller, array('access' => 'r')));
	}

	function testCheckPublicLoggedIn() {
		$Access = new TestAccess();
		$this->Controller->Project = ClassRegistry::init('Project');
		$this->Controller->Component->init($this->Controller);
		$this->Controller->Component->initialize($this->Controller);
		$this->Controller->params['controller'] = 'wiki';

		$Access->isPublic = true;
		$Access->user = array();
		$this->assertTrue($Access->check($this->Controller, array('access' => 'r')));
	}

	function testCheckPublicAllowed() {
		$Access = new TestAccess();
		$this->Controller->Project = ClassRegistry::init('Project');
		$this->Controller->Component->init($this->Controller);
		$this->Controller->Component->initialize($this->Controller);
		$this->Controller->params['controller'] = 'wiki';

		$Access->isPublic = true;
		$Access->user = array();
		$this->assertTrue($Access->check($this->Controller, array('access' => 'r')));
	}

	function testCheckPrivateAnonymous() {
		$Access = new TestAccess();
		$this->Controller->Project = ClassRegistry::init('Project');
		$this->Controller->Component->init($this->Controller);
		$this->Controller->Component->initialize($this->Controller);
		$this->Controller->params['controller'] = 'wiki';

		$Access->user = array();
		$this->Controller->Project->Permission->rules('chaw', array('wiki' => array('gwoo' => 'rw')));
		$Access->isPublic = false;
		$Access->user = array();
		$this->assertFalse($Access->check($this->Controller, array('access' => 'r')));
	}

	function testCheckPrivateLoggedIn() {
		$Access = new TestAccess();
		$this->Controller->Project = ClassRegistry::init('Project');
		$this->Controller->Component->init($this->Controller);
		$this->Controller->Component->initialize($this->Controller);
		$this->Controller->params['controller'] = 'wiki';

		$Access->user = array();
		$this->Controller->Project->Permission->rules('chaw', array('wiki' => array('gwoo' => 'rw')));

		$Access->isPublic = false;
		$Access->user = array();
		$this->assertFalse($Access->check($this->Controller, array('access' => 'r')));
	}

	function testCheckPrivateAllowed() {
		$Access = new TestAccess();
		$this->Controller->Project = ClassRegistry::init('Project');
		$this->Controller->Component->init($this->Controller);
		$this->Controller->Component->initialize($this->Controller);
		$this->Controller->params['controller'] = 'wiki';

		$Access->user = array();
		$this->Controller->Project->Permission->rules('chaw', array('wiki' => array('gwoo' => 'rw')));

		$Access->isPublic = false;
		$Access->user = array();
		$this->assertFalse($Access->check($this->Controller, array('access' => 'r')));
	}

	function testInstall() {
		$Access = new TestAccess();

		$this->Controller->Project = ClassRegistry::init('Project');

		$this->Controller->params = array(
			'controller' => 'browser',
			'action' => 'index',
			'url' => array('url' => '/')
		);
		$this->__runStartup();
		$expected = array('admin' => false, 'project' => false, 'controller' => 'pages', 'action' => 'start');
		$this->assertEqual($this->Controller->testRedirect, $expected);

		$this->Controller->Project = null;

		$this->Controller->params = array(
			'controller' => 'pages',
			'action' => 'start',
			'url' => array('url' => 'start')
		);

		$this->Controller->testRedirect = null;
		$this->__runStartup();
		$expected = null;
		$this->assertEqual($this->Controller->testRedirect, $expected);

		$this->Controller->Project = ClassRegistry::init('Project');

		$this->Controller->params = array(
			'controller' => 'users',
			'action' => 'add',
			'url' => array('url' => 'users/add')
		);
		$this->Controller->testRedirect = null;
		$this->__runStartup();
		$expected = null;
		$this->assertEqual($this->Controller->testRedirect, $expected);

		$this->Controller->Session->delete('Install');
	}

	function testAccessAfterInstallationPublic() {
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

		$this->Controller->Project = ClassRegistry::init('Project');
		$this->assertTrue($this->Controller->Project->save($data));

		$this->Controller->testRedirect = null;

		$this->Controller->params = array(
			'project' => null,
			'controller' => 'users',
			'action' => 'login',
			'url' => array('url' => 'users/login')
		);

		$this->__runStartup();

		$this->assertNull($this->Controller->testRedirect);

		$this->Controller->testRedirect = null;
		$this->Controller->params = array(
			'project' => null,
			'controller' => 'users',
			'action' => 'logout',
			'url' => array('url' => 'users/logout')
		);
		$this->__runStartup();
		$this->assertNull($this->Controller->testRedirect);

		$this->Controller->testRedirect = null;
		$this->Controller->params = array(
			'project' => null,
			'controller' => 'users',
			'action' => 'account',
			'url' => array('url' => 'users/account')
		);

		$this->Controller->Auth->mapActions(array(
			'account' => 'update', 'change' => 'update'
		));
		$this->__runStartup();

		$result = $this->Controller->testRedirect;
		$expected = '/users/login';
		$this->assertEqual($result, $expected);
	}

	function testAccessAfterInstallationPrivate() {
		$data = array('Project' =>array(
			'id' => 1,
			'name' => 'original project',
			'user_id' => 1,
			'username' => 'gwoo',
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

		$this->Controller->Project = ClassRegistry::init('Project');
		$this->assertTrue($this->Controller->Project->save($data));

		$this->Controller->testRedirect = null;

		$this->Controller->params = array(
			'project' => null,
			'controller' => 'users',
			'action' => 'login',
			'url' => array('url' => 'users/login')
		);
		$this->__runStartup();

		$this->assertNull($this->Controller->testRedirect);

		$this->Controller->testRedirect = null;
		$this->Controller->params = array(
			'project' => null,
			'controller' => 'users',
			'action' => 'logout',
			'url' => array('url' => 'users/logout')
		);
		$this->__runStartup();
		$this->assertNull($this->Controller->testRedirect);

		$this->Controller->testRedirect = null;
		$this->Controller->params = array(
			'project' => null,
			'controller' => 'users',
			'action' => 'account',
			'url' => array('url' => 'users/account')
		);

		$this->Controller->Auth->mapActions(array(
			'account' => 'update', 'change' => 'update'
		));
		$this->__runStartup();

		$result = $this->Controller->testRedirect;
		$expected = '/users/login';
		$this->assertEqual($result, $expected);
	}


	function testOwnerAndinstalled() {
		$data = array('Project' =>array(
			'id' => 1,
			'name' => 'Chaw',
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

		$this->Controller->Project = ClassRegistry::init('Project');
		$this->assertTrue($this->Controller->Project->save($data));

		$this->Controller->testRedirect = null;

		$this->Controller->params = array(
			'project' => null,
			'controller' => 'browser',
			'action' => 'index',
			'url' => array('url' => 'browser')
		);

		$this->Controller->Component->init($this->Controller);
		$this->Controller->Component->initialize($this->Controller);
		$this->Controller->Session->write('Auth.User', array('id' => 1, 'username' => 'gwoo'));

		$this->__runStartup();
		$this->assertNull($this->Controller->testRedirect);
		$this->assertTrue($this->Controller->params['isOwner']);

		$this->Controller->Session->del('Auth.User');
	}


	function testAnonymousAndPublic() {
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

		$this->Controller->Project = ClassRegistry::init('Project');
		$this->assertTrue($this->Controller->Project->save($data));

		$this->Controller->testRedirect = null;

		$this->Controller->params = array(
			'project' => null,
			'controller' => 'browser',
			'action' => 'index',
			'url' => array('url' => 'browser')
		);

		$this->__runStartup();

		$this->assertNull($this->Controller->testRedirect);

		$this->Controller->testRedirect = null;

		$this->Controller->params = array(
			'project' => 'original_project',
			'controller' => 'projects',
			'action' => 'index',
			'url' => array('url' => 'projects')
		);

		$this->__runStartup();
		$this->assertNull($this->Controller->testRedirect);

		$this->Controller->testRedirect = null;

		$this->Controller->params = array(
			'project' => 'original_project',
			'controller' => 'tickets',
			'action' => 'add',
			'url' => array('url' => 'tickets/add')
		);

		$this->__runStartup();
		$this->assertEqual($this->Controller->testRedirect, '/users/login');

		$this->Controller->testRedirect = null;

		$this->Controller->params = array(
			'project' => 'original_project',
			'controller' => 'commits',
			'action' => 'view',
			'url' => array('url' => 'commits/view/1234567890iuytrewq23456')
		);

		$this->__runStartup();
		$this->assertNull($this->Controller->testRedirect);
	}

	function testAnonymousAndPrivate() {
		$data = array('Project' =>array(
			'id' => 1,
			'name' => 'Chaw',
			'user_id' => 1,
			'username' => 'gwoo',
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

		$this->Controller->Project = ClassRegistry::init('Project');
		$this->assertTrue($this->Controller->Project->save($data));

		$this->Controller->testRedirect = null;

		$this->Controller->params = array(
			'project' => null,
			'controller' => 'browser',
			'action' => 'index',
			'url' => array('url' => 'browser')
		);

		$this->__runStartup();
		$expected = array('admin' => false, 'project'=> false, 'fork'=> false, 'controller' => 'projects', 'action' => 'index');
		$this->assertEqual($this->Controller->testRedirect, $expected);
		$this->assertFalse($this->Controller->params['isAdmin']);

		$this->Controller->testRedirect = null;

		$this->Controller->params = array(
			'project' => null,
			'controller' => 'tickets',
			'action' => 'add',
			'url' => array('url' => 'tickets/add')
		);
		$this->__runStartup();

		$this->assertEqual($this->Controller->testRedirect, '/users/login');
		$this->assertFalse($this->Controller->params['isAdmin']);

		$this->Controller->testRedirect = null;
		$this->Controller->params = array(
			'project' => null,
			'controller' => 'projects',
			'action' => 'index',
			'url' => array('url' => 'projects')
		);

		$this->__runStartup();
		$this->assertNull($this->Controller->testRedirect);

	}

	function testUserAndPrivate() {
		$data = array('Project' =>array(
			'id' => 1,
			'name' => 'Chaw',
			'user_id' => 1,
			'username' => 'gwoo',
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

		$this->Controller->Project = ClassRegistry::init('Project');
		$this->assertTrue($this->Controller->Project->save($data));

		$this->Controller->testRedirect = null;

		$this->Controller->params = array(
			'project' => null,
			'controller' => 'browser',
			'action' => 'index',
			'url' => array('url' => 'browser')
		);

		$this->Controller->Component->init($this->Controller);
		$this->Controller->Component->initialize($this->Controller);
		$this->Controller->Session->write('Auth.User', array('id' => 4, 'username' => 'bob'));

		$this->__runStartup();
		$this->assertTrue(strpos($this->Controller->testRedirect, 'test.php') !== false);
		$this->assertFalse($this->Controller->params['isAdmin']);

		$this->Controller->Session->del('Auth.User');
	}

	function testUserAndPublic() {
		$data = array('Project' =>array(
			'id' => 1,
			'name' => 'Chaw',
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

		$this->Controller->Project = ClassRegistry::init('Project');
		$this->assertTrue($this->Controller->Project->save($data));

		$this->Controller->testRedirect = null;

		$this->Controller->params = array(
			'project' => null,
			'controller' => 'tickets',
			'action' => 'add',
			'url' => array('url' => 'tickets/add')
		);

		$this->Controller->Component->init($this->Controller);
		$this->Controller->Component->initialize($this->Controller);
		$this->Controller->Session->write('Auth.User', array('id' => 4, 'username' => 'bob'));

		$this->__runStartup();

		$this->assertNull($this->Controller->testRedirect);
		$this->assertFalse($this->Controller->params['isAdmin']);


		$this->Controller->testRedirect = null;

		$this->Controller->params = array(
			'project' => null,
			'controller' => 'projects',
			'action' => 'index',
			'named' => array('type' => 'fork'),
			'url' => array('url' => 'projects/index/type:fork')
		);

		$this->Controller->Session->write('Auth.User', array('id' => 4, 'username' => 'bob'));

		$this->__runStartup();

		$this->assertNull($this->Controller->testRedirect);
		$this->assertFalse($this->Controller->params['isAdmin']);

		$this->Controller->Session->del('Auth.User');
	}


	function startTest() {
		$this->__cleanUp();

		$this->Controller = new TestAccessController();
		$this->Controller->params['url']['url'] = '/';

		Configure::write('Content', array(
			'base' => TMP . 'tests' . DS,
			'git' => TMP . 'tests' . DS . 'git' . DS,
			'svn' => TMP . 'tests' . DS . 'svn' . DS ,
		));
		$this->__projects = array(
			'One' => array(
				'id' => 1,
				'url' => 'chaw',
				'user_id' => 1,
				'repo' => array(
					'type' => 'git',
					'path' => TMP . 'tests' . DS . 'git' . DS . 'repo' . DS . 'chaw.git',
					'working' => TMP . 'tests' . DS . 'git' . DS . 'working' . DS . 'chaw'
				)
			),
			'Two' => array(
				'id' => 2,
				'url' => 'project_two',
				'user_id' => 2,
				'repo' => array(
					'type' => 'git',
					'path' => TMP . 'tests' . DS . 'git' . DS . 'repo' . DS . 'project_two.git',
					'working' => TMP . 'tests' . DS . 'git' .DS . 'working' . DS . 'project_two'
				)
			)
		);

		Configure::write('Project', $this->__projects['One']);
		$Permission = ClassRegistry::init('Permission');

		$data['Permission']['fine_grained'] = "
			[wiki]
			* = r

			[tickets]
			* = r
		";

		$Permission->saveFile($data);
	}

	function endTest() {
		$this->__cleanUp();
	}

	function __cleanUp() {
		unset($_SESSION);
		$Cleanup = new Folder(TMP . 'tests/git');
		if ($Cleanup->pwd() == TMP . 'tests/git') {
			$Cleanup->delete();
		}
		$path = Configure::read('Content.base');
		@unlink($path . 'chaw');
		@unlink($path . 'permissions.ini');
	}
}
?>