<?php
require_once(CONSOLE_LIBS . 'shell.php');
require_once(APP . 'vendors' . DS . 'shells' . DS . 'post_receive.php');
class TestShellDispatcher extends Object {

	function stdout() {
		return func_get_args();
	}

	function stdin() {
		return func_get_args();
	}

	function stderr() {
		return func_get_args();
	}
}

class TestPostReceive extends PostReceiveShell {

}

class PostReceiveTest extends CakeTestCase {
	var $fixtures = array(
		'app.project', 'app.permission', 'app.user', 'app.wiki',
		'app.timeline', 'app.comment', 'app.ticket', 'app.version',
		'app.tag', 'app.tags_tickets', 'app.commit'
	);

	function startTest() {
		$dispatcher = new TestShellDispatcher();
		$this->PostReceive = new TestPostReceive($dispatcher);

		Configure::write('Content.git', TMP . 'tests/git/');
		$this->__repos[1] = array(
			'class' => 'Repo.Git',
			'type' => 'git',
			'path' => TMP . 'tests/git/repo/test.git',
			'working' => TMP . 'tests/git/working/test',
			'chmod' => 0777
		);
		$this->PostReceive->Project = ClassRegistry::init('Project');
		$this->PostReceive->Commit = ClassRegistry::init('Commit');


		$data = array('Project' =>array(
			'id' => 1,
			'name' => 'test',
			'user_id' => 1,
			'username' => 'gwoo',
			'repo_type' => 'Git',
			'private' => 0,
			'config' => array(
				'groups' => 'user, docs team, developer, admin',
				'ticket' => array(
					'types' => 'rfc, bug, enhancement',
					'statuses' => 'pending, approved, in progress, on hold, closed',
					'priorities' => 'low, normal, high',
				)
			),
			'description' => 'this is a test project',
			'active' => 1,
			'approved' => 1,
			'remote' => 'git@git.chaw'
		));

		$this->assertTrue($this->PostReceive->Project->save($data));
		$this->assertTrue(file_exists(TMP . 'tests/git/repo/test.git'));
		$this->assertTrue(file_exists(TMP . 'tests/git/working/test/master/.git'));

		$File = new File(TMP . 'tests/git/working/test/master/.gitignore');
		$File->write('this is something new');

		$this->PostReceive->Project->Repo->commit(array("-m", "'Updating git ignore'"));
		$this->PostReceive->Project->Repo->push();


		$File = new File(TMP . 'tests/git/working/test/master/new.txt');
		$File->write('definitely new');

		$this->PostReceive->Project->Repo->commit(array("-m", "'adding new.txt'"));
		$this->PostReceive->Project->Repo->push();
		$this->PostReceive->Project->Repo->update();

		$_SERVER['PHP_CHAWUSER'] = 'gwoo';
	}

	function end() {
		parent::end();
		$this->__cleanUp();
	}

	function __cleanUp() {
		$Cleanup = new Folder(TMP . 'tests/git');
		if ($Cleanup->pwd() == TMP . 'tests/git') {
			$Cleanup->delete();
		}
	}

	function testPushNewBranch() {
		$commit = $this->PostReceive->Project->Repo->find('first');

		$oldrev = '0000000000000000000000000000000000000000';
		$newrev = $commit;

		$this->PostReceive->args = array(
			'\'test.git\'',
			'refs/heads/new',
			$oldrev,
			$newrev['revision']
		);

		$result = $this->PostReceive->commit();
		$this->assertNull($result);

		$result = $this->PostReceive->Commit->find('all');

		$expected = 'Initial Project Commit';
		$this->assertEqual($expected, $result[0]['Commit']['message']);

	}

	function testPushSingle() {
		$results = $this->PostReceive->Project->Repo->find('all', array('order' => 'asc'));
		$oldrev = $results[0];
		$newrev = $results[1];

		$this->PostReceive->args = array(
			'\'test.git\'',
			'refs/heads/master',
			$oldrev['Repo']['revision'],
			$newrev['Repo']['revision']
		);

		$result = $this->PostReceive->commit();
		$this->assertNull($result);

		$result = $this->PostReceive->Commit->find('all');

		$expected = 'Updating git ignore';
		$this->assertEqual($expected, $result[0]['Commit']['message']);

		$this->assertTrue(empty($result[1]));


		$timeline = ClassRegistry::init('Timeline');
		$result = $timeline->find('events');

		$expected = 'Updating git ignore';
		$this->assertEqual($expected, $result[1]['Commit']['message']);

		$this->assertTrue(empty($result[4]));
	}

	function testPushMultipe() {
		$this->PostReceive->Project->Repo->update();
		$results = $this->PostReceive->Project->Repo->find('all', array('order' => 'asc'));

		$oldrev = $results[0];
		$newrev = $results[2];


		$this->PostReceive->args = array(
			'\'test.git\'',
			'refs/heads/master',
			$oldrev['Repo']['revision'],
			$newrev['Repo']['revision']
		);

		$result = $this->PostReceive->commit();
		$this->assertNull($result);

		$result = $this->PostReceive->Commit->find('all');

		$expected = 'Updating git ignore';
		$this->assertEqual($expected, $result[0]['Commit']['message']);

		$expected = 'adding new.txt';
		$this->assertEqual($expected, $result[1]['Commit']['message']);


		$timeline = ClassRegistry::init('Timeline');
		$result = $timeline->find('events');

		$expected = 'Pushed 2 commits';
		$this->assertEqual($expected, $result[0]['Timeline']['event']);

		$expected = 'Updating git ignore';
		$this->assertEqual($expected, $result[1]['Commit']['message']);

		$expected = 'adding new.txt';
		$this->assertEqual($expected, $result[2]['Commit']['message']);
	}
}
?>