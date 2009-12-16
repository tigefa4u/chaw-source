<?php
/**
 * Chaw : source code and project management
 *
 * @copyright  Copyright 2009, Garrett J. Woodworth (gwoohoo@gmail.com)
 * @license    GNU AFFERO GENERAL PUBLIC LICENSE v3 (http://opensource.org/licenses/agpl-v3.html)
 *
 */

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

		$expected = array('thisisakey');
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
			'content' => 'ssh-rsa this is a key',
		));

		$this->assertTrue($SshKey->save());

		$SshKey->set(array(
			'type' => 'Git',
			'username' => 'gwoo',
			'content' => 'ssh-rsa this is another key',
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
			'ssh-rsa thisisakey',
			'ssh-rsa thisisanotherkey'
		);

		$this->assertEqual($result, $expected);
	}

	function testDeleteAfterReadAfterAppend() {
		$SshKey = new SshKey();

		$result = $SshKey->delete(array(
			'type' => 'Git', 'username' => 'gwoo',
			'content' => 'ssh-rsa thisisakey'
		));

		$this->assertTrue($result);

		$SshKey = new SshKey();

		$result = $SshKey->read(array(
			'type' => 'Git', 'username' => 'gwoo',
		));

		$expected = array(
			'ssh-rsa thisisanotherkey'
		);
		$this->assertEqual($result, $expected);

		$path = Configure::read("Content.git") . 'repo' . DS . '.ssh' . DS . 'authorized_keys';
		$Cleanup = new File($path);
		$Cleanup->delete();
	}
	
	public function testStripStuffAfter() {
		$SshKey = new SshKey();

		$SshKey->set(array(
			'type' => 'Git',
			'username' => 'gwoo',
			'content' => 'ssh-rsa this is a key== something',
		));

		$this->assertTrue($SshKey->save());

		$result = $SshKey->read(array(
			'type' => 'Git', 'username' => 'gwoo',
		));
		$expected = array(
			'ssh-rsa thisisakey=='
		);
		$this->assertEqual($result, $expected);

		$path = Configure::read("Content.git") . 'repo' . DS . '.ssh' . DS . 'authorized_keys';
		$Cleanup = new File($path);
		$Cleanup->delete();
	}
}
?>