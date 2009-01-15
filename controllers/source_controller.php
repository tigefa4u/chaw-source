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
class SourceController extends AppController {

	var $name = 'Source';

	function index() {
		$args = func_get_args();
		$path = join(DS, $args);

		$current = null;

		if ($args > 0) {
			$current = array_pop($args);
		}

		$data = $this->Source->read($this->Project->Repo, $path);

		if ($path && $current) {
			$this->pageTitle = $path;
		} else {
			$this->pageTitle = 'Source';
		}

		$this->set(compact('data', 'path', 'args', 'current'));
	}

	function branches() {

	}
}
?>