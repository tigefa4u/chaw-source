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
		$this->set(compact('data', 'args', 'current'));
	}

	function tree($branch = 'HEAD') {
		$Git = ClassRegistry::init('Git');
		$Git->repo = '/htdocs/scm/chaw.git';
		$Git->workingCopy = TMP . 'git';

		//pr($Git->tree($branch));

		pr($Git->sub('cat-file', array('-t 0ed8662ea6402467a50a6e515042485227c2dd0c')));

		pr($Git->info($branch));

		pr($Git->debug);

		die();
	}
}
?>