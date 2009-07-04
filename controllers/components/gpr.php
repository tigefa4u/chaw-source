<?php
/**
 * GprComponent
 *
 * Get, Post, Redirect component for CakePHP
 *
 * Copyright 2008, Garrett J. Woodworth <gwoo@cakephp.org>
 * Redistributions not permitted
 *
 * @copyright		Copyright 2008, Garrett J. Woodworth
 * @package			chaw
 * @subpackage		chaw.controllers.components
 * @since			Chaw 0.1
 * @license			commercial
 *
 */
class GprComponent extends Object {

	var $keys = array();

	var $actions = array();

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

	function keys($keys) {
		$this->keys = array_merge($this->keys, $keys);
	}
}
