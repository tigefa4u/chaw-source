<?php 
/* SVN FILE: $Id$ */
/* Version Test cases generated on: 2008-10-10 16:10:08 : 1223680508*/

class VersionTestCase extends CakeTestCase {
	var $Version = null;
	var $fixtures = array(
		'app.project', 'app.permission', 'app.user', 'app.wiki',
		'app.timeline', 'app.comment', 'app.ticket', 'app.version',
		'app.tag', 'app.tags_tickets', 'app.commit'
	);

	function startTest() {
		$this->Version = ClassRegistry::init('Version');
	}

	function testVersionInstance() {
		$this->assertTrue(is_a($this->Version, 'Version'));
	}

	function testVersionFind() {
		
	}
}
?>