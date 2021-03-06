<?php
/**
 * Chaw : source code and project management
 *
 * @copyright  Copyright 2009, Garrett J. Woodworth (gwoohoo@gmail.com)
 * @license    GNU AFFERO GENERAL PUBLIC LICENSE v3 (http://opensource.org/licenses/agpl-v3.html)
 *
 */

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

	function endTest() {
		unset($this->Ticket);
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
			'title' => 'First Ticket',
			'description' => 'the description',
			'previous' => '',
			'status'  => 'approved',
			'resolution' => '',
			'created'  => '2008-09-23 07:54:29',
			'modified'  => '2008-09-23 07:54:29',
		));
		$results = $this->Ticket->save($data);
		$this->assertEqual($results, true);

		$data = array('Ticket' => array(
			'id' => 3,
			'project_id'  => 1,
			'user_id'  => 1,
			'title' => 'First Ticket',
			'description' => 'the description',
			'previous' => $data['Ticket'],
			'resolution' => 'fixed',
			'comment'  => 'Lorem ipsum dolor sit amet',
			'created'  => '2008-09-23 07:54:29',
			'modified'  => '2008-09-23 07:54:29',
		));
		$results = $this->Ticket->save($data);
		$this->assertEqual($results, true);

		$this->Ticket->recursive = 1;
		$results = $this->Ticket->read();
		$this->assertEqual($results['Comment'][0]['changes'], "status:closed\nresolution:fixed");

		$data = array('Ticket' => array(
			'id' => 3,
			'project_id'  => 1,
			'user_id'  => 1,
			'title' => 'First Ticket',
			'description' => 'the description',
			'previous' => $this->Ticket->data['Ticket'],
			'event' => 'reopen',
			'resolution' => null,
			'comment'  => 'Lorem ipsum dolor sit amet',
			'created'  => '2008-09-23 07:54:29',
			'modified'  => '2008-09-23 07:54:29',
		));
		$results = $this->Ticket->save($data);
		$this->assertEqual($results, true);

		$this->Ticket->recursive = 1;
		$results = $this->Ticket->read();
		$this->assertEqual($results['Comment'][1]['changes'], "status:pending\nresolution:");
	}

	function testOwner() {
		$this->Ticket->Owner->create(array('username' => 'gwoo', 'email' => 'gwoo@test.com'));
		$this->Ticket->Owner->save();

		$data = array('Ticket' => array(
			'id' => 3,
			'project_id'  => 1,
			'user_id'  => 1,
			'title' => 'First Ticket',
			'description' => 'the description',
			'owner'  => 'gwoo',
			'previous' => '',
			'status'  => 'fixed',
			'comment' => '',
			'created'  => '2008-09-23 07:54:29',
			'modified'  => '2008-09-23 07:54:29',
		));
		$results = $this->Ticket->save($data);
		$this->assertEqual($results, true);

		$this->Ticket->recursive = 1;
		$results = $this->Ticket->find('first');
		$this->assertEqual($results['Ticket']['owner'], 1);

		$data = array('Ticket' => array(
			'id' => 3,
			'project_id'  => 1,
			'owner'  => '',
			'user_id'  => 1,
			'title' => 'First Ticket',
			'description' => 'the description',
			'previous' => array(
				'id' => 3,
				'project_id'  => 1,
				'title' => 'First Ticket',
				'description' => 'the description',
				'owner'  => 'gwoo',
				'previous' => '',
				'status'  => 'fixed',
				'modified'  => '2008-09-23 07:54:29',
			),
			'comment' => '',
			'status'  => 'fixed',
			'created'  => '2008-09-23 07:54:29',
			'modified'  => '2008-09-23 07:54:29',
		));
		$results = $this->Ticket->save($data);
		$this->assertEqual($results, true);

		$this->Ticket->recursive = 1;
		$results = $this->Ticket->find('first');
		$this->assertEqual($results['Ticket']['owner'], 0);

		$this->assertEqual($results['Comment'][0]['changes'], "owner:");


		$data = array('Ticket' => array(
			'id' => 3,
			'project_id'  => 1,
			'owner'  => 'gwoo',
			'user_id'  => 1,
			'title' => 'First Ticket',
			'description' => 'the description',
			'previous' => array(
				'id' => 3,
				'project_id'  => 1,
				'title' => 'First Ticket',
				'description' => 'the description',
				'owner'  => '',
				'previous' => '',
				'status'  => 'fixed',
				'modified'  => '2008-09-23 07:54:29',
			),
			'comment' => '',
			'status'  => 'fixed',
			'created'  => '2008-09-23 07:54:29',
			'modified'  => '2008-09-23 07:54:29'
		));
		$results = $this->Ticket->save($data);
		$this->assertEqual($results, true);

		$this->Ticket->recursive = 1;
		$results = $this->Ticket->find('first');
		$this->assertEqual($results['Ticket']['owner'], 1);

		$this->assertEqual($results['Comment'][1]['changes'], "owner:gwoo");
	}

	function testInitialStatus() {
		$this->Ticket->create();
		$this->Ticket->Owner->save(array('username' => 'gwoo', 'email' => 'gwoo@test.com'));

		$results = $this->Ticket->save(array('project_id'  => 1, 'owner'  => 'gwoo'));
		$this->assertEqual($results['Ticket']['status'], 'pending');
	}

	function testValidStateTransitions() {
		$this->Ticket->Owner->create();
		$this->Ticket->Owner->save(array('username' => 'gwoo', 'email' => 'gwoo@test.com'));

		$this->assertTrue($this->Ticket->save(array('project_id'  => 1, 'owner' => 'gwoo')));

		$result = $this->Ticket->read();
		$this->assertEqual($result['Ticket']['status'], 'pending');

		$result = $this->Ticket->events();
		$expected = array('approve', 'accept', 'hold');
		$this->assertEqual(array_keys($result), $expected);
		$this->assertEqual(array_values($result), $expected);

		$this->assertFalse($this->Ticket->reopen());
		$result = $this->Ticket->read();
		$this->assertEqual($result['Ticket']['status'], 'pending');

		$this->assertTrue($this->Ticket->approve());
		$result = $this->Ticket->read();
		$this->assertEqual($result['Ticket']['status'], 'approved');
	}

	function testStateTransitions() {
		$this->Ticket->Owner->create();
		$this->Ticket->Owner->save(array('username' => 'gwoo', 'email' => 'gwoo@test.com'));

		$this->Ticket->data = array();
		$this->assertTrue($this->Ticket->save(array(
			'title' => 'new ticket', 'description' => 'something', 'project_id'  => 1
		)));
		$result = $this->Ticket->read();
		$this->assertEqual($result['Ticket']['status'], 'pending');

		$this->Ticket->data = array();

		$this->Ticket->save(array('project_id'  => 1, 'event' => 'approve'));

		$result = $this->Ticket->read();
		$this->assertEqual($result['Ticket']['status'], 'approved');

		$this->Ticket->data = array();

		$this->Ticket->save(array(
			'project_id'  => 1, 'user_id' => 1,
			'resolution' => 'fixed',
		));

		$this->Ticket->recursive = 0;
		$result = $this->Ticket->read();
		$this->assertEqual($result['Ticket']['status'], 'closed');
		$this->assertEqual($result['Owner']['username'], 'gwoo');

	}
}

?>