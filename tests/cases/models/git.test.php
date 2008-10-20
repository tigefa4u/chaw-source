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

		$this->Git->repo = '/Volumes/Home/htdocs/chaw/content/git/repo/chaw.git';
		$this->Git->working = '/Volumes/Home/htdocs/chaw/content/git/working/chaw';

	}

	function end() {
		parent::end();
		pr($this->Git->debug);
	}

	function testGitInstance() {
		$this->assertTrue(is_a($this->Git, 'Git'));
	}

	function testCreate() {
		/*
		$path = TMP . 'tests' . DS . 'another' . DS;
		
		$this->Git->repo = $path . 'repo' . DS;
		$this->Git->working = $path . 'working' . DS;
		$this->Git->create('another');
		
		$this->assertTrue(file_exists($path . 'repo' . DS . 'another.git'));
		$this->assertTrue(file_exists($path . 'working' . DS . 'another' . DS . '.git'));
		*/
	}
	
	function testPull() {
		//$Git->pull('master');
	}
	
	function testUpdate() {
		pr($this->Git->commit("6a4766a9766652f92c0dfe0f0b990408bda91cee"));
		pr($this->Git->update());
		
	}
	
	function testCommit() {
		//pr($this->Git->sub('diff', array("a0e50432c90e3818c6083c03b7f6d3f6fda4e2c0", "2da6ad74c3e23561cb2a528283b422199a21ab11", "-p", "--unified=3")));
		//pr($this->Git->commit("refs/heads/master", "a0e50432c90e3818c6083c03b7f6d3f6fda4e2c0", "2da6ad74c3e23561cb2a528283b422199a21ab11"));
		
		//pr($this->Git->commit("a659692e6506e7d44cf29c9f3a51cb885b33b0e5"));
		
		//pr($this->Git->sub('log', array("-p", "-1", "--full-diff")));
		//pr($this->Git->findByNewrev("4952d6d310f8f2a35cfbe570f84d0aa636c3555e"));
		
	}
	
	function testPathInfo() {
		
		//pr($this->Git->pathInfo());

		//pr($this->Git->sub('cat-file', array('-t 4952d6d310f8f2a35cfbe570f84d0aa636c3555e')));
	}
	
	function testInfo() {
		
		///pr($this->Git->info('master'));

		//pr($this->Git->sub('cat-file', array('-t 4952d6d310f8f2a35cfbe570f84d0aa636c3555e')));
	}

	function testTree() {
	//	pr($this->Git->tree('master'));
	}

	
}
?>