<?php
/* SVN FILE: $Id$ */
/* Git Test cases generated on: 2008-09-09 18:09:14 : 1220999054*/
class GitTest extends CakeTestCase {

	function setUp() {
		$this->__repos[1] = array(
			'class' => 'Repo.Git',
			'type' => 'git',
			'path' => TMP . 'tests/git/repo/test.git',
			'working' => TMP . 'tests/git/working/test',
			'chmod' => 0777
		);
	}

	function end() {
		parent::end();
		$Cleanup = new Folder(TMP . 'tests/git');
		if ($Cleanup->pwd() == TMP . 'tests/git') {
			$Cleanup->delete();
		}
	}

	function testCreate() {
		$Git = ClassRegistry::init($this->__repos[1]);
		$this->assertTrue($Git->create());
		$this->assertTrue(file_exists(TMP . 'tests/git/repo/test.git'));
		$this->assertTrue(file_exists(TMP . 'tests/git/working/test/.git'));

		//pr($Git->debug);
		//pr($Git->response);
	}

	function testHook() {
		$Git = ClassRegistry::init($this->__repos[1]);
		$this->assertTrue($Git->hook('post-receive'));
		$this->assertTrue(file_exists(TMP . 'tests/git/repo/test.git/hooks/post-receive'));
		unlink(TMP . 'tests/git/repo/test.git/hooks/post-receive');
	}

	function testRead() {
		$Git = ClassRegistry::init($this->__repos[1]);
		$result = $Git->read("refs/heads/master");
		$this->assertEqual($result['message'], 'Initial Project Commit');

		//pr($Git->debug);
		//pr($Git->response);
	}

	function testFork() {
		$Git = ClassRegistry::init($this->__repos[1]);
		$result = $Git->fork("gwoo");
		$this->assertTrue(file_exists(TMP . 'tests/git/repo/forks/gwoo/test.git'));
		$this->assertTrue(file_exists(TMP . 'tests/git/working/forks/gwoo/test'));

		//pr($Git->debug);
		//pr($Git->response);

		//die();

	}

	function testFindCount() {
		$Git = ClassRegistry::init($this->__repos[1]);
		$this->assertTrue($Git->create());
		$this->assertTrue(file_exists(TMP . 'tests/git/repo/test.git'));
		$this->assertTrue(file_exists(TMP . 'tests/git/working/test/.git'));

		$File = new File(TMP . 'tests/git/working/test/.gitignore');
		$File->write('this is something new');

		$Git->commit(array("-m", "'Updating git ignore'"));
		$Git->push();

		$result = $Git->find('count', array('path' => TMP . 'tests/git/working/test/.gitignore'));

		$this->assertEqual($result, 2);
	}

	function testFindAll() {
		$Git = ClassRegistry::init($this->__repos[1]);
		$this->assertTrue($Git->create());
		$this->assertTrue(file_exists(TMP . 'tests/git/repo/test.git'));
		$this->assertTrue(file_exists(TMP . 'tests/git/working/test/.git'));

		$File = new File(TMP . 'tests/git/working/test/.gitignore');
		$File->write('this is something new');

		$Git->commit(array("-m", "'Updating git ignore'"));
		$Git->push();

		$result = $Git->find('all', array('path' => TMP . 'tests/git/working/test/.gitignore'));

		$this->assertEqual($result[0]['Repo']['message'], 'Updating git ignore');
		$this->assertEqual($result[1]['Repo']['message'], 'Initial Project Commit');

		$result = $Git->find('all', array('path' => TMP . 'tests/git/working/test/.gitignore', 'limit' => 1));

		$this->assertEqual($result[0]['Repo']['message'], 'Updating git ignore');
		$this->assertTrue(empty($result[1]['Repo']['message']));

		$result = $Git->find('all', array('path' => TMP . 'tests/git/working/test/.gitignore', 'limit' => 1, 'page' => 2));

		$this->assertEqual($result[0]['Repo']['message'], 'Initial Project Commit');
		$this->assertTrue(empty($result[1]['Repo']['message']));
	}


	function testPathInfo() {
		//pr($Git->pathInfo());
	}


	function testPull() {
		//$Git->pull('master');
	}

	function testUpdate() {
		//pr($Git->commit("6a4766a9766652f92c0dfe0f0b990408bda91cee"));
		//pr($Git->update());
	}

	function testInfo() {
		//pr($Git->sub('cat-file', array('-t 4952d6d310f8f2a35cfbe570f84d0aa636c3555e')));
		//pr($Git->run('diff', array("a0e50432c90e3818c6083c03b7f6d3f6fda4e2c0", "2da6ad74c3e23561cb2a528283b422199a21ab11", "-p", "--unified=3")));
		//pr($Git->commit("refs/heads/master", "a0e50432c90e3818c6083c03b7f6d3f6fda4e2c0", "2da6ad74c3e23561cb2a528283b422199a21ab11"));
		//pr($Git->commit("a659692e6506e7d44cf29c9f3a51cb885b33b0e5"));

		//pr($Git->sub('log', array("-p", "-1", "--full-diff")));
		//pr($Git->findByNewrev("4952d6d310f8f2a35cfbe570f84d0aa636c3555e"));

		///pr($Git->info('master'));

		//pr($Git->sub('cat-file', array('-t 4952d6d310f8f2a35cfbe570f84d0aa636c3555e')));
	}

	function testTree() {
	//	pr($Git->tree('master'));
	}


}
?>