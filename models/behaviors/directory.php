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
 * @subpackage		chaw.models
 * @since			Chaw 0.1
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 */
class DirectoryBehavior extends ModelBehavior {

    function setup(&$Model, $settings = array()) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = array('field' => 'path');
		}
		$this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], (array)$settings);
	}

	function beforeFind(&$Model, $query) {
		extract($this->settings[$Model->alias]);

		$field = $Model->alias . '.' . $field;
		if (!empty($query['conditions'][$field])) {
			$path = $query['conditions'][$field];
			$query['conditions']["{$field} LIKE"] = "{$path}%";
		}

		if (!empty($query['conditions']) && array_key_exists($field, $query['conditions'])) {
			unset($query['conditions'][$field]);
		}

		return $query;
	}

	function beforeSave(&$Model) {
		if (!empty($Model->data[$Model->alias]['path'])) {
			if ($Model->data[$Model->alias]['path'][0] !== '/') {
				$Model->data[$Model->alias]['path'] = '/' . $Model->data[$Model->alias]['path'];
			}
		} else {
			$Model->data[$Model->alias]['path'] = '/';
		}
		return true;
	}
}