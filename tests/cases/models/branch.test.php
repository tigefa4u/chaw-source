<?php
/**
 * Short description
 *
 * Long description
 *
 * Copyright 2008, Garrett J. Woodworth <gwoo@cakephp.org>
 * Redistributions not permitted
 *
 * @copyright		Copyright 2008, Garrett J. Woodworth
 * @package			chaw
 * @subpackage		chaw.tests.cases.models
 * @since			Chaw 0.1
 * @license			commercial
 *
 */
class BranchTestCase extends CakeTestCase {
	var $Branch = null;
	var $fixtures = array('app.branch');

	function startTest() {
		$this->Branch =& ClassRegistry::init('Branch');
	}

	function testBranchInstance() {
		$this->assertTrue(is_a($this->Branch, 'Branch'));
	}

	function testBranchSave() {
		$data = array('Branch' => array(
			'project_id'  => 1,
			'name'  => 'refs/heads/master',
			'created'  => '2009-01-15 09:04:41',
			'modified'  => '2009-01-15 09:04:41'
			));

		$this->Branch->create($data);
		$this->assertTrue($this->Branch->save());
		$this->assertEqual($this->Branch->id, 1);
		

		$this->Branch->recursive = -1;
		$results = $this->Branch->find('all');
		$this->assertTrue(!empty($results));
		$this->assertEqual($results, array(array('Branch' => array(
			'id' => 1,
			'project_id'  => 1,
			'name'=> 'master',
			'ref' => 'refs/heads',
			'created'  => '2009-01-15 09:04:41',
			'modified'  => '2009-01-15 09:04:41'
		))));

		$data = array('Branch' => array(
			'project_id'  => 1,
			'name'  => 'refs/heads/master',
			'created'  => '2009-01-15 09:04:41',
			'modified'  => '2009-01-15 09:04:41'
			));
		$this->Branch->create($data);
		$this->assertFalse($this->Branch->save($data));
		$this->assertEqual($this->Branch->id, 1);

		$this->Branch->recursive = -1;
		$results = $this->Branch->find('all');
		$this->assertTrue(!empty($results));
		$this->assertEqual($results, array(array('Branch' => array(
			'id' => 1,
			'project_id'  => 1,
			'name'=> 'master',
			'ref' => 'refs/heads',
			'created'  => '2009-01-15 09:04:41',
			'modified'  => '2009-01-15 09:04:41'
		))));

	}
}
?>