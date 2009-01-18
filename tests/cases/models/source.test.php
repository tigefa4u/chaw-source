<?php
/* SVN FILE: $Id$ */
/* Source Test cases generated on: 2008-10-16 22:10:35 : 1224221135*/

class SourceTestCase extends CakeTestCase {
	var $Source = null;
	var $fixtures = array(
		'app.project', 'app.permission', 'app.user', 'app.wiki',
		'app.timeline', 'app.comment', 'app.ticket', 'app.version',
		'app.tag', 'app.tags_tickets', 'app.commit',
		'app.comment'
	);

	function startTest() {
		$this->Source =& ClassRegistry::init('Source');

		Configure::write('Content.git', TMP . 'tests/git/');
		$this->Git =& ClassRegistry::init(array(
			'class' => 'Repo.Git',
			'type' => 'git',
			'path' => TMP . 'tests/git/repo/test.git',
			'working' => TMP . 'tests/git/working/test',
			'chmod' => 0777
		));

		$this->assertTrue($this->Git->create());

		$this->Git->logResponse = true;

		$this->Git->fork('gwoo');
		$Folder = new Folder(TMP . 'tests/git/working/forks/gwoo/test/master/folder', true);
		$File = new File(TMP . 'tests/git/working/forks/gwoo/test/master/folder/file.txt', true);

		$this->Git->commit('this is a new message');
		$this->Git->push();

		$this->Git->config(array(
			'path' => TMP . 'tests/git/repo/test.git',
			'working' => TMP . 'tests/git/working/test',
		));

		$this->Git->merge('test', 'gwoo');
		$this->assertTrue(file_exists(TMP . 'tests/git/working/test/master/folder'));

		$data = $this->Git->read();
		$this->assertEqual($data['message'], 'Merge from forks/gwoo/test.git');

		$this->Git->config(array(
			'path' => TMP . 'tests/git/repo/test.git',
			'working' => TMP . 'tests/git/working/test',
		));

		$this->Svn = ClassRegistry::init(array(
			'class' => 'Repo.Svn',
			'type' => 'svn',
			'path' => TMP . 'tests/svn/repo/test',
			'working' => TMP . 'tests/svn/working/test',
			'chmod' => 0777
		));
	}

	function end() {
		parent::end();
		$Cleanup = new Folder(TMP . 'tests/git');
		if ($Cleanup->pwd() == TMP . 'tests/git') {
			$Cleanup->delete();
		}
	}

	function testSourceInstance() {
		$this->assertTrue(is_a($this->Source, 'Source'));
	}

	function testInitialize() {
		$this->Git->branch = null;
		$result = $this->Source->initialize($this->Git, array('master'));
		$this->assertEqual($result, array(
			array('branches'),
			'',
			'master'
		));
		$this->Git->branch = null;
		$result = $this->Source->initialize($this->Git, array('master', 'app', 'config'));
		$this->assertEqual($result, array(
			array('branches', 'master', 'app'),
			'app/config',
			'config'
		));
		$this->Git->branch = null;
		$result = $this->Source->initialize($this->Git, array('master', 'app', 'config', 'core.php'));
		$this->assertEqual($result, array(
			array('branches', 'master', 'app', 'config'),
			'app/config/core.php',
			'core.php'
		));
	}
	function testRebuild() {

	}

	function testRead() {
		$this->Source->initialize($this->Git);
		$result = $this->Source->read();
		$this->assertEqual($result['Folder'][0]['name'], 'master');
		$this->assertEqual($result['Folder'][0]['path'], '');
		$this->assertEqual($result['Folder'][0]['info']['message'], 'Merge from forks/gwoo/test.git');
	}
}
?>