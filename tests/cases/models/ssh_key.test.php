<?php
/* SVN FILE: $Id$ */
/* SshKey Test cases generated on: 2008-10-16 22:10:35 : 1224221135*/
App::import('Model', 'SshKey');

class SshKeyTestCase extends CakeTestCase {
	var $SshKey = null;

	function start() {
		parent::start();
		Configure::write('Content', array(
			'base' => TMP . 'tests' . DS,
			'git' => TMP . 'tests' . DS . 'git' . DS,
			'svn' => TMP . 'tests' . DS . 'svn' . DS ,
		));
	}

	function testSave() {
		$SshKey = new SshKey();
		$SshKey->set(array(
			'type' => 'Git',
			'username' => 'gwoo',
			'content' => 'this is a key',
		));

		$this->assertTrue($SshKey->save());
		$path = Configure::read("Content.git") . 'repo' . DS . '.ssh' . DS . 'authorized_keys';
		$this->assertTrue(file_exists($path));
	}

	function testRead() {
		$SshKey = new SshKey();

		$result = $SshKey->read(array(
			'type' => 'Git', 'username' => 'gwoo',
		));

		$expected = array('this is a key');
		$this->assertEqual($result, $expected);

		$path = Configure::read("Content.git") . 'repo' . DS . '.ssh' . DS . 'authorized_keys';
		$Cleanup = new File($path);
		$Cleanup->delete();
		clearstatcache();
	}

	function testAppend() {
		$SshKey = new SshKey();

		$SshKey->set(array(
			'type' => 'Git',
			'username' => 'gwoo',
			'content' => 'this is a key',
		));

		$this->assertTrue($SshKey->save());

		$SshKey->set(array(
			'type' => 'Git',
			'username' => 'gwoo',
			'content' => 'this is another key',
		));

		$this->assertTrue($SshKey->save());
		$path = Configure::read("Content.git") . 'repo' . DS . '.ssh' . DS . 'authorized_keys';
	}

	function testReadAfterAppend() {
		$SshKey = new SshKey();

		$result = $SshKey->read(array(
			'type' => 'Git', 'username' => 'gwoo',
		));

		$expected = array(
			'this is a key',
			'this is another key'
		);

		$this->assertEqual($result, $expected);
	}

	function testDeleteAfterReadAfterAppend() {
		$SshKey = new SshKey();

		$result = $SshKey->delete(array(
			'type' => 'Git', 'username' => 'gwoo',
			'content' => 'this is a key'
		));

		$this->assertTrue($result);

		$SshKey = new SshKey();

		$result = $SshKey->read(array(
			'type' => 'Git', 'username' => 'gwoo',
		));

		$expected = array(
			'this is another key'
		);
		$this->assertEqual($result, $expected);

		$path = Configure::read("Content.git") . 'repo' . DS . '.ssh' . DS . 'authorized_keys';
		$Cleanup = new File($path);
		$Cleanup->delete();

	}
}
?>