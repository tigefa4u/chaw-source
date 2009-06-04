<?php
/* SVN FILE: $Id$ */
/* Timeline Test cases generated on: 2009-04-15 13:04:08 : 1239825668*/
class TimelineTest extends CakeTestCase {

	var $fixtures = array(
		'app.project', 'app.permission', 'app.user', 'app.wiki',
		'app.timeline', 'app.comment', 'app.ticket', 'app.version',
		'app.tag', 'app.tags_tickets', 'app.commit', 'app.branch',
		'app.branches_commits'
	);

	function startCase() {
		$this->Timeline = ClassRegistry::init('Timeline');
		Configure::write('Content', array(
			'base' => TMP . 'tests' . DS,
			'git' => TMP . 'tests' . DS . 'git' . DS,
			'svn' => TMP . 'tests' . DS . 'svn' . DS ,
		));
	}

	function endCase() {
		unset($this->Timeline);
	}

	function endTest() {
		$Cleanup = new Folder(TMP . 'tests/git');
		if ($Cleanup->pwd() == TMP . 'tests/git') {
			$Cleanup->delete();
		}

		$MoreCleanup = new Folder(TMP . 'tests/svn');
		if ($MoreCleanup->pwd() == TMP . 'tests/svn') {
			$MoreCleanup->delete();
		}
		$this->__cleanUp();
	}

	function __cleanUp() {
		$path = Configure::read('Content.base');
		$Cleanup = new Folder(TMP . 'tests/git');
		if ($Cleanup->pwd() == TMP . 'tests/git') {
			$Cleanup->delete();
		}
		@unlink($path . 'chaw');
		@unlink($path . 'permissions.ini');
	}

	function testTimelineInstance() {
		$this->assertTrue(is_a($this->Timeline, 'Timeline'));
	}

	function testIsUnique() {
		$this->Timeline->create(array('model' => 'Ticket', 'foreign_key' => 1));
		$this->assertTrue($this->Timeline->save());

		$results = $this->Timeline->find('all');
		$this->assertEqual(count($results), 1);
		$this->assertEqual($results[0]['Timeline']['id'], 1);

		// $this->Timeline->create(array('model' => 'Ticket', 'foreign_key' => 1));
		// 		$this->assertFalse($this->Timeline->save());
		//
		// 		$results = $this->Timeline->find('all');
		// 		$this->assertEqual(count($results), 1);
		// 		$this->assertEqual($results[0]['Timeline']['id'], 1);
	}

	function testEvents() {
		$this->_setup();

		$results = $this->Timeline->find('events');
		$this->assertEqual(count($results), 7);

		$results = $this->Timeline->paginateCount();
		$this->assertEqual($results, 7);

		$results = $this->Timeline->paginate(array(), array(), array('Timeline.id' => 'DESC'));
		pr($results);
		
		$this->assertEqual(7, $results[0]['Timeline']['id']);
		$this->assertEqual(6, $results[0]['Commit']['id']);
		
		$this->assertEqual(3, $results[1]['Timeline']['id']);
		$this->assertEqual(2, $results[1]['Commit']['id']);
		
		$this->assertEqual(4, $results[3]['Timeline']['id']);
		$this->assertEqual(3, $results[3]['Commit']['id']);
		
		$this->assertEqual(5, $results[4]['Timeline']['id']);
		$this->assertEqual(4, $results[4]['Commit']['id']);
		
		$this->assertEqual(6, $results[4]['Timeline']['id']);
		$this->assertEqual(5, $results[4]['Commit']['id']);
		
		$this->assertEqual(1, $results[6]['Timeline']['id']);
		$this->assertEqual(1, $results[6]['Wiki']['id']);
		
	}

	function _setup() {
		$Project = ClassRegistry::init('Project');

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

		$this->assertTrue($Project->save($data));

		$Commit = ClassRegistry::init('Commit');

		$events = array(1, 2, 3);
		foreach ($events as $event) {
			$data = array('Commit' => array(
				//'id'  => 1,
				'revision'  => $event,
				'commit_date' => '2008-10-13 09:26:08',
				'message'  => 'Lorem ipsum dolor sit amet',
				'project_id'  => 1,
				'user_id' => 1,
				'branch' => 'refs/heads/master'
			));
			$Commit->create($data);
			$this->assertTrue($Commit->save());
			sleep(1);
		}

		$events = array(1, 2, 3);
		foreach ($events as $event) {
			$data = array('Commit' => array(
				//'id'  => 1,
				'revision'  => $event,
				'commit_date' => '2008-10-13 09:26:08',
				'message'  => 'Lorem ipsum dolor sit amet',
				'project_id'  => 1,
				'user_id' => 1,
				'branch' => 'refs/heads/other'
			));
			$Commit->create($data);
			$this->assertTrue($Commit->save());
			sleep(1);
		}

	}
}
?>