<?php
/* SVN FILE: $Id$ */
/* Svn Test cases generated on: 2008-08-28 13:08:20 : 1219956320*/
App::import('Model', 'Svn');
App::import('Core', 'File');
class TestSvn extends Svn {

	var $cacheSources = false;

}

class SvnTest extends CakeTestCase {

	function start() {
		parent::start();
		$this->Svn = new TestSvn();

		$this->Svn->repo = TMP . 'svn/repo';

		$this->Svn->workingCopy = TMP . 'demo';
	}

	function end() {
		parent::end();
		pr($this->Svn->debug);
	}

	function getTests() {
		return array_merge(array('start', 'startCase'), array('testCreate', 'testCommit'), array('end', 'endCase'));
	}

	function testSvnInstance() {
		$this->assertTrue(is_a($this->Svn, 'Svn'));
	}

	function testCreate() {
		$this->Svn->create('demo', TMP . 'svn/repo');
	}

	function testRead() {
		$this->Svn->repo = TMP . 'svn/repo';

		$this->Svn->workingCopy = TMP . 'demo';

		pr($this->Svn->info('/branches'));

		pr($this->Svn->look('author', $this->Svn->repo));
	}

	function testCommit() {
		$this->Svn->workingCopy = TMP . 'demo';
		$File = new File($this->Svn->workingCopy . '/branches/demo_1.0.x.x/index.php', true);
		$File->write("this is a new php file with plain text");
		pr($this->Svn->sub('add', array(dirname($File->pwd()))));
		pr($this->Svn->sub('commit', array($this->Svn->workingCopy, '--message "Adding index.php"')));
		pr($this->Svn->info('/branches/demo_1.0.x.x/index.php'));
	}

	function testTree() {
		$this->Svn->repo = TMP . 'svn/repo';
		$this->Svn->repo = 'https://svn.cakephp.org/repo/branches';
		$this->Svn->workingCopy = TMP . 'demo';

		pr($this->Svn->look('tree', $this->Svn->workingCopy));
	}

	function testCheckout() {
		pr($this->Svn->sub('co', array(
			'https://svn.cakephp.org/repo/branches/1.2.x.x/cake',
			$this->Svn->workingCopy .'/branches/demo_1.0.x.x/cake', '--force'
		)));
	}

	function testBlame() {
		$this->Svn->workingCopy = '/Volumes/Home/htdocs/cake/repo/branches/1.2.x.x';
		pr($this->Svn->sub('blame', $this->Svn->workingCopy . '/cake/libs/file.php'));
	}
}
?>