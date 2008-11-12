<?php
/* SVN FILE: $Id$ */
/* Svn Test cases generated on: 2008-08-28 13:08:20 : 1219956320*/
class SvnTest extends CakeTestCase {

	function setUp() {
		$this->__repos[1] = array(
			'class' => 'Repo.Svn',
			'type' => 'svn',
			'path' => TMP . 'tests/svn/repo/test',
			'working' => TMP . 'tests/svn/working/test',
			'chmod' => 0777
		);
	}

	function end() {
		parent::end();
		$Cleanup = new Folder(TMP . 'tests/svn');
		if ($Cleanup->pwd() == TMP . 'tests/svn') {
			$Cleanup->delete();
		}
	}

	function getTests() {
		return array_merge(
			array('start', 'startCase'),
			array(
				'testCreate', 'testHook', 'testRead', 'testCommit', 'testFind',
			),
			array('end', 'endCase')
		);
	}


	function testCreate() {
		$Svn = ClassRegistry::init($this->__repos[1]);
		$this->assertTrue($Svn->create());
		$this->assertTrue(file_exists($Svn->config['path']));
		$this->assertTrue(file_exists($Svn->config['working']));

		$this->assertTrue(file_exists($Svn->config['path'] . DS .'conf' . DS . 'svnserve.conf'));
		$result = file_get_contents($Svn->config['path'] . DS .'conf' . DS . 'svnserve.conf');
		$expected = "[general]\nauthz-db = ../permissions.ini\n";
		$this->assertEqual($result, $expected);

		//pr($Svn->debug);
		//pr($Svn->response);
	}

	function testHook() {
		$Svn = ClassRegistry::init($this->__repos[1]);
		$Svn->hook('post-commit');
		$this->assertTrue(file_exists($Svn->path . DS . 'hooks' . DS . 'post-commit'));
	}

	function testRead() {
		$Svn = ClassRegistry::init($this->__repos[1]);
		$result = $Svn->read(1);
		$this->assertEqual($result['revision'], 1);
		$this->assertEqual($result['message'], 'Initial Project Import');

		//var_dump($result);
		//var_dump($Svn->debug);
		//var_dump($Svn->response);
	}
	
	
	function testCommit() {
		$Svn = ClassRegistry::init($this->__repos[1]);

		$File = new File($Svn->working . '/branches/demo_1.0.x.x/index.php', true);
		$File->write("this is a new php file with plain text");

		$result = $Svn->run('add', array(dirname($File->pwd())));
		//var_dump($result);
		
		$result = $Svn->run('commit', array($Svn->working, '--message "Adding index.php"'));
		//var_dump($result);
		
		$result = $Svn->info('/branches/demo_1.0.x.x/index.php');
		//var_dump($result);
	}
	
	function testFind() {
		$Svn = ClassRegistry::init($this->__repos[1]);
		$result = $Svn->find();
		
		$this->assertEqual($result[0]['Repo']['revision'], 1);
		$this->assertEqual($result[0]['Repo']['message'], 'Initial Project Import');
		
		$this->assertEqual($result[1]['Repo']['revision'], 2);
		$this->assertEqual($result[1]['Repo']['message'], 'Adding index.php');
		
		var_dump($result);
		//var_dump($Svn->debug);
		//var_dump($Svn->response);
	}
	
	/*
	function testInfo() {
		pr($Svn->info());

		pr($Svn->look('author', $Svn->repo));
	}


	function testCheckout() {
		pr($Svn->run('co', array(
			'https://svn.cakephp.org/repo/branches/1.2.x.x/cake',
			$Svn->workingCopy .'/branches/demo_1.0.x.x/cake', '--force'
		)));
	}

	function testBlame() {
		$Svn = ClassRegistry::init($this->__repos[1]);
		pr($Svn->run('blame', $Svn->working . '/cake/libs/file.php'));
	}
	*/
}
?>