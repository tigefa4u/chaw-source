<?php
/**
 * Short description
 *
 * Long description
 *
 *
 * Copyright 2008, Garrett J. Woodworth <gwoo@cakephp.org>
 * Licensed under The MIT License
 * Redistributions of files must retain the copyright notice.
 *
 * @copyright		Copyright 2008, Garrett J. Woodworth
 * @package			chaw
 * @subpackage		chaw.controllers
 * @since			Chaw 0.1
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 */
class BrowserController extends AppController {

	var $name = 'Browser';

	function index() {
		$args = func_get_args();
		$path = join(DS, $args);

		$current = null;

		if ($args > 0) {
			$current = array_pop($args);
		}

		$this->Browser->Repo = $this->Project->Repo;
		$data = $this->Browser->read($path);
		
		if ($path && $current) {
			$this->pageTitle = $path;
		} else {
			$this->pageTitle = 'Source';
		}

		$this->set(compact('data', 'path', 'args', 'current'));
	}
}
?>