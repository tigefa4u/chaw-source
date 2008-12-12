<?php
/**
 * Short description
 *
 * Long description
 *
 * Copyright 2008, Garrett J. Woodworth <gwoo@cakephp.org>
 * Redistributions not permitted
 *
 * @copyright		Copyright 2008, Garrett J. Woodworth
 * @package			chaw
 * @subpackage		chaw.controllers
 * @since			Chaw 0.1
 * @license			commercial
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