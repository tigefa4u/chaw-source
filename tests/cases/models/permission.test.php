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
		$this->Permission = new TestPermission();
	}

	function testPermissionInstance() {
		$this->assertTrue(is_a($this->Permission, 'Permission'));
	}

	function testFile() {
		Configure::write('Project', array(
			'url' => 'chaw',
			'repo' => array(
				'type' => 'git',
				'path' => APP . 'content' . DS . 'git' . DS . 'repo' . DS . 'chaw.git',
				'working' => APP . 'content' . DS . 'git' .DS . 'chaw'
			)
		));
		
		$this->assertTrue($this->Permission->check("chaw:/refs/head/master", array('group' => 'amf-developers', 'access' => 'rw')));
		
		$this->assertFalse($this->Permission->check("chaw:/refs/head/master", array('user' => 'gwoo', 'access' => 'w')));
		
		$this->assertFalse($this->Permission->check("chaw:/refs/head/master", array('user' => 'gwoo', 'access' => 'w')));
		
		$this->assertTrue($this->Permission->check("chaw:/refs/head/master", array('user' => 'gwoo', 'access' => 'r')));
		
		pr($this->Permission->groups());
	}
}
?>