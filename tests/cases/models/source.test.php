<?php
/* SVN FILE: $Id$ */
/* Source Test cases generated on: 2008-10-16 22:10:35 : 1224221135*/

class SourceTestCase extends CakeTestCase {
	var $Source = null;
	var $fixtures = array(
		'app.project', 'app.permission', 'app.user', 'app.wiki',
		'app.timeline', 'app.comment', 'app.ticket', 'app.version',
		'app.tag', 'app.tags_tickets', 'app.commit',
		'app.comment'
	);

	function startTest() {
		$this->Source =& ClassRegistry::init('Source');
	}

	function testSourceInstance() {
		$this->assertTrue(is_a($this->Source, 'Source'));
	}

	function testSourceRead() {

	}
}
?>