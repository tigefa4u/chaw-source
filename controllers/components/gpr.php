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
class GprComponent extends Object {

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $keys = array();

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $actions = array();

	/**
	 * undocumented function
	 *
	 * @param string $controller
	 * @param string $options
	 * @return void
	 */
	function initialize($controller, $options) {
		$this->params = $_GET;
		unset($this->params['url']);

		if (!empty($options['keys'])) {
			$this->keys($options['keys']);
		}
		if (!empty($options['connect'])) {
			Router::connectNamed($options['connect']);
		} else {
			Router::connectNamed($this->keys);
		}

		if (!empty($options['count'])) {
			$this->enabled = count($this->params) >= $options['count'] ? true : false;
		} else {
			$this->enabled = count($this->params) >= count($this->keys) ? true : false;
		}
		if (!empty($options['actions'])) {
			$this->allowedActions = $options['actions'];
		}

		if (!$this->enabled) {
			foreach ($this->keys as $key) {
				if (isset($controller->params['named'][$key])) {
					if (strpos($controller->params['named'][$key], ',') !== false) {
						$controller->data[$controller->modelClass][$key] = explode(',', $controller->params['named'][$key]);
					} else {
						$controller->data[$controller->modelClass][$key] = $controller->params['named'][$key];
					}
				}
			}
		}
	}

	/**
	 * undocumented function
	 *
	 * @param string $controller
	 * @return void
	 */
	function startup($controller) {
		if (in_array($controller->action, $this->allowedActions)) {
			foreach ($this->keys as $key) {
				if (!empty($controller->params['url'][$key])) {
					$controller->passedArgs[$key] = $controller->params['named'][$key] = join(',', (array) $controller->params['url'][$key]);
				} elseif (!empty($controller->passedArgs[$key])) {
					$controller->passedArgs[$key] = $controller->params['named'] = null;
				}
			}
			if (!empty($this->params)) {
				$controller->redirect($controller->passedArgs, 303);
			}
		}
	}

	/**
	 * undocumented function
	 *
	 * @param string $keys
	 * @return void
	 */
	function keys($keys) {
		$this->keys = array_merge($this->keys, $keys);
	}
}
