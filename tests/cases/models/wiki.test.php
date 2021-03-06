<?php
/**
 * Chaw : source code and project management
 *
 * @copyright  Copyright 2009, Garrett J. Woodworth (gwoohoo@gmail.com)
 * @license    GNU AFFERO GENERAL PUBLIC LICENSE v3 (http://opensource.org/licenses/agpl-v3.html)
 *
 */

App::import('Model', 'Wiki');

class WikiTestCase extends CakeTestCase {
	var $Wiki = null;
	var $fixtures = array(
		'app.project', 'app.permission', 'app.user', 'app.wiki',
		'app.timeline', 'app.comment', 'app.ticket', 'app.version',
		'app.tag', 'app.tags_tickets', 'app.commit', 'app.branch'
	);

	function startCase() {
		$this->Wiki = ClassRegistry::init('Wiki');
	}

	function testWikiInstance() {
		$this->assertTrue(is_a($this->Wiki, 'Wiki'));
	}

	function testSave() {
		$this->Wiki->create(array(
			'title' => 'home',
			'path' => '',
		));
		$this->assertTrue($this->Wiki->save());

		$this->Wiki->create(array(
			'title' => 'setup',
			'path' => 'guides',
		));
		$this->assertTrue($this->Wiki->save());

		$this->Wiki->create(array(
			'title' => 'keygen',
			'path' => 'guides/ssh',
		));
		$this->assertTrue($this->Wiki->save());
	}

	function testWikiSaveWithSpaceInTitle() {
		$this->Wiki->create(array(
			'title' => 'some space',
			'path' => 'guides/ssh',
		));
		$this->assertTrue($this->Wiki->save());

		$result = $this->Wiki->field('slug');
		$this->assertEqual($result, 'some_space');

		$this->Wiki->create(array(
			'slug' => 'another space',
			'path' => 'guides/ssh',
		));
		$this->assertTrue($this->Wiki->save());

		$result = $this->Wiki->field('slug');
		$this->assertEqual($result, 'another_space');
	}


	function testPath() {
		$this->Wiki->create(array(
			'title' => 'home',
			'path' => '/',
		));
		$this->assertTrue($this->Wiki->save());

		$this->Wiki->create(array(
			'title' => 'setup',
			'path' => '/guides',
		));
		$this->assertTrue($this->Wiki->save());

		$this->Wiki->create(array(
			'title' => 'keygen',
			'path' => '/guides/ssh',
		));
		$this->assertTrue($this->Wiki->save());


		$results = $this->Wiki->find('all', array('conditions' => array('Wiki.path' => '/')));
		$this->assertEqual(count($results), 3);
		$this->assertEqual($results[0]['Wiki']['path'], '/');
		$this->assertEqual($results[1]['Wiki']['path'], '/guides');
		$this->assertEqual($results[2]['Wiki']['path'], '/guides/ssh');

		$results = $this->Wiki->find('all', array('conditions' => array('Wiki.path' => '/guides')));
		$this->assertEqual(count($results), 2);
		$this->assertEqual($results[0]['Wiki']['path'], '/guides');
		$this->assertEqual($results[1]['Wiki']['path'], '/guides/ssh');

		$results = $this->Wiki->find('all', array('conditions' => array('Wiki.path' => '/guides/ssh')));
		$this->assertEqual(count($results), 1);
		$this->assertEqual($results[0]['Wiki']['path'], '/guides/ssh');
	}

	function testFindSuperList() {
		$this->testSave();
		$results = $this->Wiki->find('superList', array('fields' => array('id', 'path', 'slug')));
		$expected = array(
			'1' => '/ home',
			'2' => '/guides setup',
			'3' => '/guides/ssh keygen'
		);
		$this->assertEqual($results, $expected);

		$results = $this->Wiki->find('superList', array(
			'fields' => array('id', 'path', 'slug'),
			'separator' => '/'
		));
		$expected = array(
			'1' => '//home',
			'2' => '/guides/setup',
			'3' => '/guides/ssh/keygen'
		);
		$this->assertEqual($results, $expected);
	}

	function testActivate() {
		$this->Wiki->create(array(
			'title' => 'keygen',
			'path' => '/guides/ssh',
			'content' => 'ok cool'
		));
		$this->assertTrue($this->Wiki->save());

		$data = $this->Wiki->find('first');
		$results = $this->Wiki->activate($data);
		$this->assertEqual($results, true);

		$data = $this->Wiki->find('first');
		$this->assertEqual($results['Wiki']['active'], 1);
	}

	function testSlug() {
		$result = $this->Wiki->slug('this-is-hypenated');
		$this->assertEqual($result, 'this-is-hypenated');
	}
}
?>