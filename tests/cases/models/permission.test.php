<?php
/* SVN FILE: $Id$ */
/* Permission Test cases generated on: 2008-10-17 12:10:29 : 1224273329*/
App::import('Model', 'Permission');
class TestPermission extends Permission {

	var $cacheSources = false;
}

class PermissionTest extends CakeTestCase {

	function start() {
		parent::start();
		Configure::write('Content', array(
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

	}

	function testSaveFile() {
		Configure::write('Project', $this->__projects['One']);
		$Permission = new TestPermission();

		$data['Permission']['fine_grained'] = "
		[chaw:/refs/heads/master]
		gwoo = r
		@chaw-developers = rw

		[chaw:/wiki/add]
		gwoo = rw

		[chaw:/test/deny/all]
		* =

		[chaw:/tickets/add]
		gwoo = rw

		[project_two:/test/override]
		gwoo = rw

		[groups]
		chaw-developers = gwoo, bob, tom";

		$Permission->saveFile($data);

		$this->assertTrue(file_exists(TMP . 'tests' . DS . 'git' . DS . 'repo' . DS . 'permissions.ini'));


		Configure::write('Project', $this->__projects['Two']);
		$Permission = new TestPermission();

		$data['Permission']['fine_grained'] = "
		[/refs/heads/master]
		gwoo = r
		@project_two-developers = rw

		[/test/override]
		gwoo = r

		[groups]
		project_two-developers = gwoo, nate, larry";

		$Permission->saveFile($data);

		$this->assertTrue(file_exists(TMP . 'tests' . DS . 'git' . DS . 'repo' . DS . 'project_two.git' . DS . 'permissions.ini'));


	}

	function testRules() {
		Configure::write('Project', $this->__projects['One']);
		$Permission = new TestPermission();

		$result = $Permission->rules();
//		pr($result);
		$expected = array(
			'chaw' => array(
				'/refs/heads/master' => array(
					'gwoo' => 'r',
					'@chaw-developers' => 'rw'
				),
				'/wiki/add' => array(
					'gwoo' => 'rw'
				),
				'/test/deny/all' => array(
					'*' => ''
				),
				'/tickets/add' => array(
					'gwoo' => 'rw'
				),
			),
			'project_two' => array(
				'/test/override' => array(
					'gwoo' => 'rw'
				)
			),
			'groups' => array(
				'chaw-developers' => array('gwoo', 'bob', 'tom')
			)
		);
		$this->assertEqual($result, $expected);


		Configure::write('Project', $this->__projects['Two']);
		$Permission = new TestPermission();

		$result = $Permission->rules();
//		pr($result);
		$expected = array(
			'project_two' => array(
				'/refs/heads/master' => array(
					'gwoo' => 'r',
					'@project_two-developers' => 'rw'
				),
				'/test/override' => array(
					'gwoo' => 'rw'
				)
			),
			'groups' => array(
				'project_two-developers' => array('gwoo', 'nate', 'larry'),
				'chaw-developers' => array('gwoo', 'bob', 'tom')
			)
		);
		$this->assertEqual($result, $expected);
	}

	function testGroups() {
		Configure::write('Project', $this->__projects['One']);
		$Permission = new TestPermission();

		$result = $Permission->groups();
		$expected = array(
			array('Group' =>
				array(
					'name' => 'chaw-developers',
					'users' => array('gwoo', 'bob', 'tom')
				)
			)
		);
		$this->assertEqual($result, $expected);
	}

	function testCheck() {
		Configure::write('Project', $this->__projects['One']);
		$Permission = new TestPermission();

		$this->assertTrue($Permission->check("/refs/heads/master", array('group' => 'chaw-developers', 'access' => 'rw')));

		$this->assertFalse($Permission->check("/refs/heads/master", array('user' => 'gwoo', 'access' => 'w')));

		$this->assertFalse($Permission->check("/refs/heads/master", array('user' => 'gwoo', 'access' => 'w')));

		$this->assertTrue($Permission->check("/tickets/add", array('user' => 'gwoo', 'access' => 'r')));

		$this->assertTrue($Permission->check("/refs/heads/master", array('user' => 'gwoo', 'access' => 'r')));

		$this->assertFalse($Permission->check("/test/deny/all", array('user' => 'gwoo', 'access' => 'r')));


		Configure::write('Project', $this->__projects['Two']);
		$Permission = new TestPermission();

		$this->assertTrue($Permission->check("/refs/heads/master", array('group' => 'project_two-developers', 'access' => 'rw')));

		$this->assertFalse($Permission->check("/refs/heads/master", array('user' => 'gwoo', 'access' => 'w')));

		$this->assertFalse($Permission->check("/refs/heads/master", array('user' => 'gwoo', 'access' => 'w')));

		$this->assertTrue($Permission->check("/refs/heads/master", array('user' => 'gwoo', 'access' => 'r')));

		$this->assertTrue($Permission->check("/test/override", array('user' => 'gwoo', 'access' => 'r')));

	}
}
?>