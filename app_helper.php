<?php
/**
 * Chaw : source code and project management
 *
 * @copyright  Copyright 2009, Garrett J. Woodworth (gwoohoo@gmail.com)
 * @license    GNU AFFERO GENERAL PUBLIC LICENSE v3 (http://opensource.org/licenses/agpl-v3.html)
 *
 */
/**
 * undocumented class
 *
 * @package default
 */
class AppHelper extends Helper {
	
	/**
	 * undocumented function
	 *
	 * @param string $url 
	 * @param string $full 
	 * @return void
	 */
	function url($url = null, $full = false) {
		if (is_array($url)) {
			if (!empty($this->params['project'])) {
				$url = array_merge(array('project' => $this->params['project']), $url);
			}
			if (!empty($this->params['fork'])) {
				$url = array_merge(array('fork' => $this->params['fork']), $url);
			}
		}
		return Router::url($url, $full);
	}
}
?>