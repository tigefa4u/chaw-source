<?php
/* SVN FILE: $Id$ */
/* Ticket Test cases generated on: 2008-12-08 15:12:43 : 1228778743*/
class TicketTest extends CakeTestCase {

	var $fixtures = array(
		'app.project', 'app.permission', 'app.user', 'app.wiki',
		'app.timeline', 'app.comment', 'app.ticket', 'app.version',
		'app.tag', 'app.tags_tickets', 'app.commit',
		'app.ticket'
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
}
?>