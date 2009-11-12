<?php
/* SVN FILE: $Id$ */
/* Project Fixture generated on: 2008-10-06 23:10:38 : 1223348498*/

class ProjectFixture extends CakeTestFixture {
	var $name = 'Project';
	
	function __construct() {
		parent::__construct();
		$Schema = new CakeSchema(array(
			'name' => 'RadDev',
		));
		$Schema = $Schema->load();
		$this->fields = $Schema->tables['projects'];
	}
}
?>
