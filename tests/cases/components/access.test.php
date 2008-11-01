<?php
/* SVN FILE: $Id$ */
/* Access Test cases generated on: 2008-11-01 10:11:29 : 1225561949*/
App::import('Component', 'Access');
class TestAccess extends AccessComponent {
}

class AccessComponentTest extends CakeTestCase {

	function testAccessInstance() {
		$this->assertTrue(is_a($this->Access, 'AccessComponent'));
	}

	function testInitialize() {

		$Access = new TestAccess($controller);
	}
}
?>