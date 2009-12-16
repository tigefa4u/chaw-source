<?php
/**
 * Chaw : source code and project management
 *
 * @copyright  Copyright 2009, Garrett J. Woodworth (gwoohoo@gmail.com)
 * @license    GNU AFFERO GENERAL PUBLIC LICENSE v3 (http://opensource.org/licenses/agpl-v3.html)
 *
 */

class ProjectFixture extends CakeTestFixture {
	var $name = 'Project';

	function __construct() {
		parent::__construct();
		$Schema = new CakeSchema(array(
			'name' => 'Chaw',
		));
		$Schema = $Schema->load();
		$this->fields = $Schema->tables['projects'];
	}
}
?>