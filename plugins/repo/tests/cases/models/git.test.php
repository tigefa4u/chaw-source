<?php
/* SVN FILE: $Id$ */
/* Git Test cases generated on: 2008-09-09 18:09:14 : 1220999054*/
class GitTest extends CakeTestCase {

	function startTest() {
		Configure::write('Content.git', TMP . 'tests/git/');
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
		$result = $Git->read();
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

	function testFindWithFields() {
		$Git = ClassRegistry::init($this->__repos[1]);
		$this->assertTrue($Git->create());
		$this->assertTrue(file_exists(TMP . 'tests/git/repo/test.git'));
		$this->assertTrue(file_exists(TMP . 'tests/git/working/test/.git'));

		$File = new File(TMP . 'tests/git/working/test/.gitignore');
		$File->write('this is something new');

		$Git->commit(array("-m", "'Updating git ignore'"));
		$Git->push();

		$result = $Git->find(array(), array('email', 'author'));
		$this->assertEqual($result, array('email' => 'gwoo@cakephp.org', 'author' => 'gwoo'));

		$result = $Git->find(array());
		unset($result['hash']);
		$this->assertEqual($result, array(
			'email' => 'gwoo@cakephp.org',
			'author' => 'gwoo',
			'committer' => 'gwoo',
			'committer_email' => 'gwoo@cakephp.org',
			'subject' => 'Updating git ignore'
		));

		$Git = ClassRegistry::init(array(
			'class' => 'Repo.Git',
			'type' => 'git',
			'path' => '/Volumes/Home/htdocs/chaw_content/git/repo/renan.git',
			'working' => '/Volumes/Home/htdocs/chaw_content/git/working/renan',
			'chmod' => 0777
		));
		$result = $Git->find(array('commit' => 'fdb86255e698e9d873620ca5e14470eca60a7560'), array('email', 'author'));
		$this->assertEqual($result, array('email' => 'renan.saddam@gmail.com', 'author' => 'renan.saddam'));
	}

	function testCommitIntoBranch() {
		$Git = ClassRegistry::init($this->__repos[1]);
		$this->assertTrue($Git->create());
		$this->assertTrue(file_exists(TMP . 'tests/git/repo/test.git'));
		$this->assertTrue(file_exists(TMP . 'tests/git/working/test/.git'));

		$File = new File(TMP . 'tests/git/working/test/.gitignore');
		$File->write('this is something new');

		$Git->branch('new', true);

		$Git->commit(array("-m", "'Updating git ignore'"));
		$Git->push('origin', 'new');

		//pr($Git->debug);
		//pr($Git->response);
	}

	function testFastForward() {
		$Cleanup = new Folder(TMP . 'tests/git');
		if ($Cleanup->pwd() == TMP . 'tests/git') {
			$Cleanup->delete();
		}
		$Git = ClassRegistry::init($this->__repos[1]);
		$this->assertTrue($Git->create());
		$this->assertTrue(file_exists(TMP . 'tests/git/repo/test.git'));
		$this->assertTrue(file_exists(TMP . 'tests/git/working/test/.git'));

		$result = $Git->fork("gwoo");
		$this->assertTrue(file_exists(TMP . 'tests/git/repo/forks/gwoo/test.git'));
		$this->assertTrue(file_exists(TMP . 'tests/git/working/forks/gwoo/test'));

		//pr($Git->debug);
		// /pr($Git->response);

		$File = new File(TMP . 'tests/git/working/test/new.text', true);
		$File->write('this is something new');

		$Git = ClassRegistry::init($this->__repos[1]);
		$Git->commit(array("-m", "'Pushing to parent'"));
		$Git->push('origin', 'master');

		$Git->fork("gwoo");
		pr($Git->merge("test"));
		
		$this->assertTrue(file_exists(TMP . 'tests/git/working/forks/gwoo/test/new.text'));
		
		pr($Git->debug);
		//pr($Git->response);
		//die();
	}
	
	function testMergeFromFork() {
		$Cleanup = new Folder(TMP . 'tests/git');
		if ($Cleanup->pwd() == TMP . 'tests/git') {
			$Cleanup->delete();
		}
		$Git = ClassRegistry::init($this->__repos[1]);
		$this->assertTrue($Git->create());
		$this->assertTrue(file_exists(TMP . 'tests/git/repo/test.git'));
		$this->assertTrue(file_exists(TMP . 'tests/git/working/test/.git'));

		$result = $Git->fork("gwoo");
		$this->assertTrue(file_exists(TMP . 'tests/git/repo/forks/gwoo/test.git'));
		$this->assertTrue(file_exists(TMP . 'tests/git/working/forks/gwoo/test'));
		
		$File = new File(TMP . 'tests/git/working/forks/gwoo/test/new.text', true);
		$File->write('this is something new');
		$Git->commit(array("-m", "'Pushing to fork'"));
		$Git->push('origin', 'master');
		
		$Git = ClassRegistry::init($this->__repos[1]);
		
		$File = new File(TMP . 'tests/git/working/test/other.text', true);
		$File->write('this is something elese is new');
		$Git->commit(array("-m", "'Pushing to parent'"));
		$Git->push('origin', 'master');
		
		$Git->update('origin', 'master');
		$Git->before(array("cd {$Git->working}"));
		$Git->remote(array('add', 'gwoo', TMP . 'tests/git/repo/forks/gwoo/test.git'));
		$Git->update('gwoo', 'master');
		$Git->push('origin', 'master');
		
		$data = $Git->read();
		$this->assertTrue($data['message'], 'Pushing to fork');
		$this->assertTrue(file_exists(TMP . 'tests/git/working/test/new.text'));
		$this->assertTrue(file_exists(TMP . 'tests/git/working/test/other.text'));
		
		pr($Git->debug);
		pr($Git->response);
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