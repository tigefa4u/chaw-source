<?php 
/* SVN FILE: $Id$ */
/* Version Test cases generated on: 2008-10-10 16:10:08 : 1223680508*/
App::import('Model', 'Version');

class TestVersion extends Version {
	var $cacheSources = false;
	var $useDbConfig  = 'test_suite';
}

class VersionTestCase extends CakeTestCase {
	var $Version = null;
	var $fixtures = array('app.version');

	function start() {
		parent::start();
		$this->Version = new TestVersion();
	}

	function testVersionInstance() {
		$this->assertTrue(is_a($this->Version, 'Version'));
	}

	function testVersionFind() {
		$this->Version->recursive = -1;
		$results = $this->Version->find('first');
		$this->assertTrue(!empty($results));

		$expected = array('Version' => array(
			'id'  => 1,
			'title'  => 'Lorem ipsum dolor sit amet',
			'description'  => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida,
									phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam,
									vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit,
									feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.
									Orci aliquet, in lorem et velit maecenas luctus, wisi nulla at, mauris nam ut a, lorem et et elit eu.
									Sed dui facilisi, adipiscing mollis lacus congue integer, faucibus consectetuer eros amet sit sit,
									magna dolor posuere. Placeat et, ac occaecat rutrum ante ut fusce. Sit velit sit porttitor non enim purus,
									id semper consectetuer justo enim, nulla etiam quis justo condimentum vel, malesuada ligula arcu. Nisl neque,
									ligula cras suscipit nunc eget, et tellus in varius urna odio est. Fuga urna dis metus euismod laoreet orci,
									litora luctus suspendisse sed id luctus ut. Pede volutpat quam vitae, ut ornare wisi. Velit dis tincidunt,
									pede vel eleifend nec curabitur dui pellentesque, volutpat taciti aliquet vivamus viverra, eget tellus ut
									feugiat lacinia mauris sed, lacinia et felis.',
			'created'  => '2008-10-10 16:15:08',
			'modified'  => '2008-10-10 16:15:08',
			'completed'  => 1,
			'due'  => '2008-10-10'
			));
		$this->assertEqual($results, $expected);
	}
}
?>