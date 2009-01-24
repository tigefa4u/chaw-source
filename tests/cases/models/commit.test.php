<?php
/* SVN FILE: $Id$ */
/* Commit Test cases generated on: 2008-10-13 09:10:08 : 1223915168*/
class CommitTestCase extends CakeTestCase {
	var $Commit = null;
	var $fixtures = array(
		'app.project', 'app.permission', 'app.user', 'app.wiki',
		'app.timeline', 'app.comment', 'app.ticket', 'app.version',
		'app.tag', 'app.tags_tickets', 'app.commit', 'app.branch'
	);

	function startTest() {
		$this->Commit = ClassRegistry::init('Commit');
	}

	function testCommitInstance() {
		$this->assertTrue(is_a($this->Commit, 'Commit'));
	}

	function testCommitSave() {
		$data = array('Commit' => array(
			//'id'  => 1,
			'revision'  => 2,
			'author' => 'gwoo',
			'commit_date' => '2008-10-13 09:26:08',
			'message'  => 'Lorem ipsum dolor sit amet',
			'created'  => '2008-10-13 09:26:08',
			'modified'  => '2008-10-13 09:26:08',
			'project_id'  => 1,
			'user_id' => '',
			'branch' => 'refs/heads/master'
			));

		$results = $this->Commit->save($data);
		$this->assertEqual($results['Commit']['revision'], $data['Commit']['revision']);
		$this->assertEqual($results['Commit']['branch_id'], 1);


		$Timeline = ClassRegistry::init('Timeline');
		$Timeline->recursive = -1;
		$results = $Timeline->find('first');
		unset($results['Timeline']['created'], $results['Timeline']['modified']);
		$this->assertEqual($results, array('Timeline' => array(
			'id' => 1,
			'project_id' => 1,
			'model' => 'Commit',
			'foreign_key' => 1,
		)));

	}

	function testCommitAggregate() {
		$data = array('Commit' => array(
			//'id'  => 1,
			'revision'  => 2,
			'author' => 'gwoo',
			'commit_date' => '2008-10-13 09:26:08',
			'message'  => 'Lorem ipsum dolor sit amet',
			'created'  => '2008-10-13 09:26:08',
			'modified'  => '2008-10-13 09:26:08',
			'project_id'  => 1,
			'user_id' => ''
			));

		$results = $this->Commit->save($data);
		$this->assertEqual($results, $data);

		$Timeline = ClassRegistry::init('Timeline');
		$Timeline->recursive = -1;
		$results = $Timeline->find('first');
		unset($results['Timeline']['created'], $results['Timeline']['modified']);
		$this->assertEqual($results, array('Timeline' => array(
			'id' => 1,
			'project_id' => 1,
			'model' => 'Commit',
			'foreign_key' => 1,
		)));
	}
}
?>