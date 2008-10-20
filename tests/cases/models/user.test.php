<?php 
/* SVN FILE: $Id$ */
/* User Test cases generated on: 2008-10-16 10:10:23 : 1224177023*/
App::import('Model', 'User');

class UserTestCase extends CakeTestCase {
	var $User = null;
	var $fixtures = array('app.user');

	function start() {
		parent::start();
		$this->User =& ClassRegistry::init('User');
	}

	function testUserInstance() {
		$this->assertTrue(is_a($this->User, 'User'));
	}

	function testUserFind() {
		$this->User->recursive = -1;
		$results = $this->User->find('first');
		$this->assertTrue(!empty($results));

		$expected = array('User' => array(
			'id'  => 1,
			'username'  => 'Lorem ipsum dolor sit amet',
			'password'  => 'Lorem ipsum dolor sit amet',
			'group_id'  => 'Lorem ipsum dolor sit amet',
			'email'  => 'Lorem ipsum dolor sit amet',
			'last_login'  => '2008-10-16 10:10:23',
			'created'  => '2008-10-16 10:10:23',
			'modified'  => '2008-10-16 10:10:23'
			));
		$this->assertEqual($results, $expected);
	}
}
?>