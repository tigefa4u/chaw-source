<?php 
/* SVN FILE: $Id$ */
/* Ticket Test cases generated on: 2008-09-23 07:09:29 : 1222170869*/
App::import('Model', 'Ticket');

class TestTicket extends Ticket {
	var $cacheSources = false;
	var $useDbConfig  = 'test_suite';
}

class TicketTestCase extends CakeTestCase {
	var $Ticket = null;
	var $fixtures = array('app.ticket');

	function start() {
		parent::start();
		$this->Ticket = new TestTicket();
	}

	function testTicketInstance() {
		$this->assertTrue(is_a($this->Ticket, 'Ticket'));
	}

	function testTicketFind() {
		$this->Ticket->recursive = -1;
		$results = $this->Ticket->find('first');
		$this->assertTrue(!empty($results));

		$expected = array('Ticket' => array(
			'id'  => 1,
			'type_id'  => 1,
			'feature_id'  => 1,
			'owner'  => 1,
			'reporter'  => 1,
			'summary'  => 'Lorem ipsum dolor sit amet',
			'body'  => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida,
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
			'created'  => '2008-09-23 07:54:29',
			'modified'  => '2008-09-23 07:54:29',
			'status'  => 1
			));
		$this->assertEqual($results, $expected);
	}
}
?>