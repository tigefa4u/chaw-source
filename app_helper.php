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
 * @subpackage		chaw
 * @since			Chaw 0.1
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 */
class AppHelper extends Helper {

	function url($url = null, $full = false) {
		if (is_array($url) && !empty($this->params['project'])) {
			$url = array_merge(array('project' => $this->params['project']), $url);
		}
		return Router::url($url, $full);
	}
}
?>