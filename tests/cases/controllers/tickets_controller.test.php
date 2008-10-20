<?php 
/* SVN FILE: $Id$ */
/* TicketsController Test cases generated on: 2008-09-23 07:09:01 : 1222170901*/
App::import('Controller', 'Tickets');

class TestTickets extends TicketsController {
	var $autoRender = false;
}

class TicketsControllerTest extends CakeTestCase {
	var $Tickets = null;

	function setUp() {
		$this->Tickets = new TestTickets();
		$this->Tickets->constructClasses();
	}

	function testTicketsControllerInstance() {
		$this->assertTrue(is_a($this->Tickets, 'TicketsController'));
	}

	function tearDown() {
		unset($this->Tickets);
	}
}
?>