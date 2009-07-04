<?php
/* SVN FILE: $Id$ */
/* Timeline Test cases generated on: 2009-04-15 13:04:08 : 1239825668*/
class TimelineTest extends CakeTestCase {

	var $fixtures = array(
		'app.project', 'app.permission', 'app.user', 'app.wiki',
		'app.timeline', 'app.comment', 'app.ticket', 'app.version',
		'app.tag', 'app.tags_tickets', 'app.commit', 'app.branch'
	);

	function startCase() {
		$this->Timeline = ClassRegistry::init('Timeline');
	}

	function endCase() {
		unset($this->Timeline);
	}

	function testTimelineInstance() {
		$this->assertTrue(is_a($this->Timeline, 'Timeline'));
	}

	function testIsUnique() {
		$this->Timeline->create(array('model' => 'Ticket', 'foreign_key' => 1));
		$this->assertTrue($this->Timeline->save());

		$results = $this->Timeline->find('all');
		$this->assertEqual(count($results), 1);
		$this->assertEqual($results[0]['Timeline']['id'], 1);

		// $this->Timeline->create(array('model' => 'Ticket', 'foreign_key' => 1));
		// 		$this->assertFalse($this->Timeline->save());
		// 		
		// 		$results = $this->Timeline->find('all');
		// 		$this->assertEqual(count($results), 1);
		// 		$this->assertEqual($results[0]['Timeline']['id'], 1);
	}
}
?>