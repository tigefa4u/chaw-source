<?php
/* SVN FILE: $Id$ */
/* Directory Test cases generated on: 2008-11-25 18:11:31 : 1227667771*/
App::import('AppModel');
App::import('Behavior', 'Directory');
class TestDirectoryBehavior extends DirectoryBehavior {
}

class TestDirectory extends AppModel {

	var $useTable = 'wiki';

	var $actsAs = array('Directory');
}

class DirectoryBehaviorTest extends CakeTestCase {

	var $fixtures = array('app.wiki');

	function start() {
		parent::start();
		$this->Directory = ClassRegistry::init('TestDirectory');
	}

	function testDirectoryInstance() {
		$this->assertTrue(is_a($this->Directory, 'TestDirectory'));
	}

	function testSave() {
		$this->Directory->create(array(
			'slug' => 'home',
			'path' => '',
		));
		$this->assertTrue($this->Directory->save());

		$this->Directory->create(array(
			'slug' => 'setup',
			'path' => 'guides',
		));
		$this->assertTrue($this->Directory->save());

		$this->Directory->create(array(
			'slug' => 'keygen',
			'path' => 'guides/ssh',
		));
		$this->assertTrue($this->Directory->save());

	}


	function testPath() {
		$this->Directory->create(array(
			'slug' => 'home',
			'path' => '/',
		));
		$this->assertTrue($this->Directory->save());

		$this->Directory->create(array(
			'slug' => 'setup',
			'path' => '/guides',
		));
		$this->assertTrue($this->Directory->save());

		$this->Directory->create(array(
			'slug' => 'keygen',
			'path' => '/guides/ssh',
		));
		$this->assertTrue($this->Directory->save());

		$this->Directory->create(array(
			'slug' => 'keygen',
			'path' => '/guides/ssh/keys',
		));
		$this->assertTrue($this->Directory->save());


		$results = $this->Directory->find('all', array('conditions' => array('TestDirectory.path' => '/')));
		$this->assertEqual(count($results), 2);
		$this->assertEqual($results[0]['TestDirectory']['path'], '/');
		$this->assertEqual($results[1]['TestDirectory']['path'], '/guides');

		$this->assertTrue(empty($results[2]['TestDirectory']['path']));

		$results = $this->Directory->find('all', array('conditions' => array('TestDirectory.path' => '/guides')));
		$this->assertEqual(count($results), 1);
		$this->assertEqual($results[0]['TestDirectory']['path'], '/guides');
		//$this->assertEqual($results[1]['TestDirectory']['path'], '/guides/ssh');

		$results = $this->Directory->find('all', array('conditions' => array('TestDirectory.path' => '/guides/ssh')));
		$this->assertEqual(count($results), 1);
		$this->assertEqual($results[0]['TestDirectory']['path'], '/guides/ssh');
		//$this->assertEqual($results[1]['TestDirectory']['path'], '/guides/ssh/keys');

	}
}
?>