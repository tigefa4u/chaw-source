<?php 
/* SVN FILE: $Id$ */
/* Git Test cases generated on: 2008-09-09 18:09:14 : 1220999054*/
App::import('Model', 'Git');
class TestGit extends Git {

	var $cacheSources = false;
}

class GitTest extends CakeTestCase {

	function start() {
		parent::start();
		$this->Git = new TestGit();
		
		$this->Git->repo = '/htdocs/scm/creampuff.git';
		$this->Git->workingCopy = TMP . 'git';
		
	}
	
	function end() {
		parent::end();
		pr($Git->debug);
	}

	function testGitInstance() {
		$this->assertTrue(is_a($this->Git, 'Git'));
	}

	function testInfo() {
		pr($Git->info($branch));
		
		pr($this->Git->sub('cat-file', array('-t 0ed8662ea6402467a50a6e515042485227c2dd0c')));
	}

	function testTree() {
		pr($this->Git->tree($branch));
	}

	function testPull() {
		//$Git->pull('master');
	}
}
?>