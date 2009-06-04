<?php
require_once(CONSOLE_LIBS . 'shell.php');
require_once(APP . 'vendors' . DS . 'shells' . DS . 'post_receive.php');
class PostReceiveTest extends CakeTestCase {
	var $fixtures = array(
		'app.project', 'app.permission', 'app.user', 'app.wiki',
		'app.timeline', 'app.comment', 'app.ticket', 'app.version',
		'app.tag', 'app.tags_tickets', 'app.commit', 'app.branch',
		'app.branches_commits'
	);

	function startTest() {
		$dispatcher = null;
		$this->PostReceive = new PostReceiveShell($dispatcher);
		
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
			'name' => 'original project',
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
		
		$this->PostReceive->args = array(
			'\'original_project.git\'',
			'refs/heads/master',
			'oldrev',
			'newrev'
		);
		
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
	
	function testPostReceiveInstance() {
		$this->assertTrue(is_a($this->PostReceive, 'PostReceiveShell'));
	}
	
	function testCommitSingle() {
		$result = $this->PostReceive->commit();
		$this->assertNull($result);
	}
	
}
?>