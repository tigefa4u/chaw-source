<?php 
/* SVN FILE: $Id$ */
/* Project Test cases generated on: 2008-10-06 15:10:20 : 1223321240*/
App::import('Model', 'Project');

class TestProject extends Project {
	var $cacheSources = false;
	var $useDbConfig  = 'test_suite';
}

class ProjectTestCase extends CakeTestCase {
	var $Project = null;
	var $fixtures = array('app.project');

	function start() {
		parent::start();
		$this->Project = new TestProject();
	}

	function testProjectInstance() {
		$this->assertTrue(is_a($this->Project, 'Project'));
	}
	
	function testProjectSave() {
		$data = array('Project' => array(
			'repo_type' => 'Git',
			'',
		));
	}

	function testProjectFind() {
		
	}
}
?>