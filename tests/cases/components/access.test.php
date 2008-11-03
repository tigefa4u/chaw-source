<?php
/* SVN FILE: $Id$ */
/* Access Test cases generated on: 2008-11-01 10:11:29 : 1225561949*/
App::import('Component', array('Auth', 'Session', 'Access'));
App::import('Controller');
App::import('Model', array('Project', 'Permission'));

class TestAccess extends AccessComponent {
}

class TestAccessController extends Controller {

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

	function testInitialize() {
		$Access = new TestAccess();

		$Access->user = array('User' => array(
			'id' => 1, 'username' => 'gwoo'
		));

		$this->Controller->params['url']['url'] = '/';
		$result = $Access->startup($this->Controller);
		$expected = true;
		$this->assertEqual($result, $expected);

		$result = $this->Controller->testRedirect;
		$expected = array('admin' => false, 'project' => false, 'controller' => 'pages', 'action' => 'start');
		$this->assertEqual($result, $expected);
		$this->Controller->testRedirect = null;

		$this->Controller->params['url']['url'] = 'start';
		$result = $Access->startup($this->Controller);
		$expected = true;
		$this->assertEqual($result, $expected);
		$this->assertNull($this->Controller->testRedirect);

	}


	function start() {
		parent::start();

		$this->Controller = new TestAccessController();
		$this->Controller->Auth = new AuthComponent();
		$this->Controller->Session = new SessionComponent();

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