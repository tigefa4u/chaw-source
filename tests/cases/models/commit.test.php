<?php
/* SVN FILE: $Id$ */
/* Commit Test cases generated on: 2008-10-13 09:10:08 : 1223915168*/
class CommitTestCase extends CakeTestCase {
	var $Commit = null;
	var $fixtures = array(
		'app.project', 'app.permission', 'app.user', 'app.wiki',
		'app.timeline', 'app.comment', 'app.ticket', 'app.version',
		'app.tag', 'app.tags_tickets', 'app.commit',
	);

	function startTest() {
		$this->Commit = ClassRegistry::init('Commit');
	}

	function testCommitInstance() {
		$this->assertTrue(is_a($this->Commit, 'Commit'));
	}
/*
	function testCommitFind() {
		$this->Commit->recursive = -1;
		$results = $this->Commit->find('first');
		$this->assertTrue(!empty($results));

		$expected = array('Commit' => array(
			'id'  => 1,
			'revision'  => 1,
			'message'  => 'Lorem ipsum dolor sit amet',
			'diff'  => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida,
									phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam,
									vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit,
									feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.
									Orci aliquet, in lorem et velit maecenas luctus, wisi nulla at, mauris nam ut a, lorem et et elit eu.
									Sed dui facilisi, adipiscing mollis lacus congue integer, faucibus consectetuer eros amet sit sit,
									magna dolor posuere. Placeat et, ac occaecat rutrum ante ut fusce. Sit velit sit porttitor non enim purus,
									id semper consectetuer justo enim, nulla etiam quis justo condimentum vel, malesuada ligula arcu. Nisl neque,
									ligula cras suscipit nunc eget, et tellus in varius urna odio est. Fuga urna dis metus euismod laoreet orci,
									litora luctus suspendisse sed id luctus ut. Pede volutpat quam vitae, ut ornare wisi. Velit dis tincidunt,
									pede vel eleifend nec curabitur dui pellentesque, volutpat taciti aliquet vivamus viverra, eget tellus ut
									feugiat lacinia mauris sed, lacinia et felis.',
			'created'  => '2008-10-13 09:26:08',
			'modified'  => '2008-10-13 09:26:08',
			'project_id'  => 1
			));
		$this->assertEqual($results, $expected);
	}
*/
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