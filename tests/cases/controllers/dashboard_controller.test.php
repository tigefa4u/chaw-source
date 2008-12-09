<?php 
/* SVN FILE: $Id$ */
/* DashboardController Test cases generated on: 2008-10-16 12:10:35 : 1224184475*/
App::import('Controller', 'Dashboard');

class TestDashboard extends DashboardController {
	var $autoRender = false;
}

class DashboardControllerTest extends CakeTestCase {
	var $Dashboard = null;
	var $fixtures = array(
		'app.project', 'app.permission', 'app.user', 'app.wiki',
		'app.timeline', 'app.comment', 'app.ticket', 'app.version',
		'app.tag', 'app.tags_tickets', 'app.commit'
	);

	function startTest() {
		$this->Dashboard = new TestDashboard();
		$this->Dashboard->constructClasses();
	}

	function testDashboardControllerInstance() {
		$this->assertTrue(is_a($this->Dashboard, 'DashboardController'));
	}

	function endTest() {
		unset($this->Dashboard);
	}
}
?>