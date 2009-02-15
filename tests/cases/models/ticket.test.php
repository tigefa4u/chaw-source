<?php
/* SVN FILE: $Id$ */
/* Ticket Test cases generated on: 2008-12-08 15:12:43 : 1228778743*/
class TicketTest extends CakeTestCase {

	var $fixtures = array(
		'app.project', 'app.permission', 'app.user', 'app.wiki',
		'app.timeline', 'app.comment', 'app.ticket', 'app.version',
		'app.tag', 'app.tags_tickets', 'app.commit',
		'app.ticket', 'app.branch'
	);

	function startTest() {
		$this->Ticket = ClassRegistry::init('Ticket');
	}

	function testTicketInstance() {
		$this->assertTrue(is_a($this->Ticket, 'Ticket'));
	}

	function testSave() {

		$all = array(
			array('Ticket' => array(
				'project_id'  => 1,
				'version_id' => 1,
				'reporter'  => 1,
				'owner'  => 1,
				'type'  => 'bug',
				'status'  => 'open',
				'priority' => 'high',
				'title'  => 'Lorem ipsum dolor sit amet',
				'descripiton'  => 'Lorem ipsum dolor sit amet',
				'created'  => '2008-09-23 07:54:29',
				'modified'  => '2008-09-23 07:54:29',
			)),
			array('Ticket' => array(
				'project_id'  => 1,
				'version_id' => 1,
				'reporter'  => 1,
				'owner'  => 1,
				'type'  => 'bug',
				'status'  => 'open',
				'priority' => 'high',
				'title'  => 'Lorem ipsum dolor sit amet',
				'descripiton'  => 'Lorem ipsum dolor sit amet',
				'created'  => '2008-09-23 07:54:29',
				'modified'  => '2008-09-23 07:54:29',
			)),
			array('Ticket' => array(
				'project_id'  => 1,
				'version_id' => 1,
				'reporter'  => 1,
				'owner'  => 1,
				'type'  => 'bug',
				'status'  => 'open',
				'priority' => 'high',
				'title'  => 'Lorem ipsum dolor sit amet',
				'descripiton'  => 'Lorem ipsum dolor sit amet',
				'created'  => '2008-09-23 07:54:29',
				'modified'  => '2008-09-23 07:54:29',
			)),
			array('Ticket' => array(
				'project_id'  => 1,
				'version_id' => 1,
				'reporter'  => 1,
				'owner'  => 1,
				'type'  => 'bug',
				'status'  => 'open',
				'priority' => 'high',
				'title'  => 'Lorem ipsum dolor sit amet',
				'descripiton'  => 'Lorem ipsum dolor sit amet',
				'created'  => '2008-09-23 07:54:29',
				'modified'  => '2008-09-23 07:54:29',
			))
		);

		$results = $this->Ticket->saveAll($all);
		$this->assertEqual($results, true);

		$this->Ticket->recursive = -1;
		$results = $this->Ticket->find('count');
		$this->assertEqual($results, 4);
	}

	function testModify() {
		$this->testSave();
		$data = array('Ticket' => array(
			'id' => 3,
			'project_id'  => 1,
			'user_id'  => 1,
			'previous' => '',
			'comment'  => 'Lorem ipsum dolor sit amet',
			'created'  => '2008-09-23 07:54:29',
			'modified'  => '2008-09-23 07:54:29',
		));
		$results = $this->Ticket->save($data);
		$this->assertEqual($results, true);

		$data = array('Ticket' => array(
			'id' => 3,
			'project_id'  => 1,
			'user_id'  => 1,
			'previous' => '',
			'status'  => 'fixed',
			'created'  => '2008-09-23 07:54:29',
			'modified'  => '2008-09-23 07:54:29',
		));
		$results = $this->Ticket->save($data);
		$this->assertEqual($results, true);
	}

	function testOwner() {
		$this->Ticket->Owner->create(array('username' => 'gwoo', 'email' => 'gwoo@test.com'));
		$this->Ticket->Owner->save();

		$data = array('Ticket' => array(
			'id' => 3,
			'project_id'  => 1,
			'owner'  => 'gwoo',
			'previous' => '',
			'status'  => 'fixed',
			'created'  => '2008-09-23 07:54:29',
			'modified'  => '2008-09-23 07:54:29'
		));
		$results = $this->Ticket->save($data);
		$this->assertEqual($results, true);

		$results = $this->Ticket->find('first');
		$this->assertEqual($results['Ticket']['owner'], 1);
	}

	function testInitialStatus() {
		$this->Ticket->create();
		$this->Ticket->Owner->save(array('username' => 'gwoo', 'email' => 'gwoo@test.com'));

		$results = $this->Ticket->save(array('project_id'  => 1, 'owner'  => 'gwoo'));
		$this->assertEqual($results['Ticket']['status'], 'pending');
	}

	function testValidStateTransitions() {
		$this->Ticket->create();
		$this->Ticket->Owner->save(array('username' => 'gwoo', 'email' => 'gwoo@test.com'));
		$this->Ticket->save(array('project_id'  => 1, 'owner'  => 'gwoo'));

		$result = $this->Ticket->read();
		$this->assertEqual($result['Ticket']['status'], 'pending');

		$result = $this->Ticket->events();
		$expected = array('approve', 'accept', 'hold', 'close');
		$this->assertEqual($result, $expected);

		$this->assertFalse($this->Ticket->reopen());
		$result = $this->Ticket->read();
		$this->assertEqual($result['Ticket']['status'], 'pending');

		$this->assertTrue($this->Ticket->approve());
		$result = $this->Ticket->read();
		$this->assertEqual($result['Ticket']['status'], 'approved');
	}
}

?>