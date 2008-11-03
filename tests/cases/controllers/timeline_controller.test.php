<?php 
/* SVN FILE: $Id$ */
/* TimelineController Test cases generated on: 2008-10-06 15:10:08 : 1223321528*/
App::import('Controller', 'Timeline');

class TestTimeline extends TimelineController {
	var $autoRender = false;
}

class TimelineControllerTest extends CakeTestCase {
	var $Timeline = null;

	function setUp() {
		$this->Timeline = new TestTimeline();
		$this->Timeline->constructClasses();
	}

	function testTimelineControllerInstance() {
		$this->assertTrue(is_a($this->Timeline, 'TimelineController'));
	}

	function tearDown() {
		unset($this->Timeline);
	}
}
?>