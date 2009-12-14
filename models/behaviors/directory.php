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
class DirectoryBehavior extends ModelBehavior {

	/**
	 * undocumented function
	 *
	 * @param string $Model
	 * @param string $settings
	 * @return void
	 */
    function setup(&$Model, $settings = array()) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = array('field' => 'path');
		}
		$this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], (array)$settings);
	}

	/**
	 * undocumented function
	 *
	 * @param string $Model
	 * @param string $query
	 * @return void
	 */
	function beforeFind(&$Model, $query) {
		extract($this->settings[$Model->alias]);

		$field = $Model->alias . '.' . $field;
		if (!empty($query['conditions'][$field])) {
			$path = $query['conditions'][$field];
			$query['conditions']["{$field} LIKE"] = "{$path}%";
			/*
			if (array_key_exists('not', $query) && $query['not'] == false) {
				if ($path == '/') {
					$path = '/%';
				}
				$not = str_replace('//', '/', $path .'/');
				$query['conditions']["{$field} NOT LIKE"] = "{$not}%";
			}
			*/
		}

		if (!empty($query['conditions']) && array_key_exists($field, $query['conditions'])) {
			unset($query['conditions'][$field]);
		}

		return $query;
	}

	/**
	 * undocumented function
	 *
	 * @param string $Model
	 * @return void
	 */
	function beforeSave(&$Model) {

		if (!empty($Model->data[$Model->alias]['path'])) {
			$Model->data[$Model->alias]['path'] = str_replace('//', '/', '/' . $Model->data[$Model->alias]['path']);
		} else {
			$Model->data[$Model->alias]['path'] = '/';
		}
		return true;
	}
}