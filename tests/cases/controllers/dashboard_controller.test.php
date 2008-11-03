<?php 
/* SVN FILE: $Id$ */
/* DashboardController Test cases generated on: 2008-10-16 12:10:35 : 1224184475*/
App::import('Controller', 'Dashboard');

class TestDashboard extends DashboardController {
	var $autoRender = false;
}

class DashboardControllerTest extends CakeTestCase {
	var $Dashboard = null;

	function setUp() {
		$this->Dashboard = new TestDashboard();
		$this->Dashboard->constructClasses();
	}

	function testDashboardControllerInstance() {
		$this->assertTrue(is_a($this->Dashboard, 'DashboardController'));
	}

	function tearDown() {
		unset($this->Dashboard);
	}
}
?>