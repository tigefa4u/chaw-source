<?php
/* SVN FILE: $Id$ */
/* Commit Test cases generated on: 2008-10-13 09:10:08 : 1223915168*/
App::import('Model', 'Commit');

class TestCommit extends Commit {
	//var $cacheSources = false;
}

class CommitTestCase extends CakeTestCase {
	var $Commit = null;
	var $fixtures = array('app.commit', 'app.timeline', 'app.project');

	function start() {
		parent::start();
		$this->Commit = new Commit();
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
			'diff'  => 'Index: /htdocs/chaw/content/svn/working/one/branches/index.php
			===================================================================
			--- /htdocs/chaw/content/svn/working/one/branches/index.php	(revision 5)
			+++ /htdocs/chaw/content/svn/working/one/branches/index.php	(revision 6)
			@@ -1,10 +1,10 @@
			 i am making a change to for file

			-i am making a change to this file
			-i am making a change to this file
			+i am gonna make a change to this file
			+i am a change to this file


			-i am making a change to this file
			+i making a to this file


			 i am making a change to this file
			\ No newline at end of file
			',
			'created'  => '2008-10-13 09:26:08',
			'modified'  => '2008-10-13 09:26:08',
			'project_id'  => 1
			));

		$results = $this->Commit->save($data);

		$this->assertEqual($results, $data);

		$this->Commit->Timeline->recursive = -1;
		$results = $this->Commit->Timeline->find('first');
		pr($results);
	}
}
?>