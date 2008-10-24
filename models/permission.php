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
class Permission extends AppModel {

	var $name = 'Permission';

	var $belongsTo = array('User', 'Project');

	var $__rules = array();

	function afterSave($created) {
		$File = $this->__getFile();
		if (!$File->exists()) {
			return false;
		}
		$File->write(trim($this->data['Permission']['fine_grained']));
	}

	function check($path, $options = array()) {
		$defaults = array('access' => 'w', 'user' => null, 'group' => null, 'project' => null);
		extract(array_merge($defaults, $options));

		if (empty($project)) {
			$config = Configure::read('Project');
			$project = $config['url'];
		}

		if (!empty($this->__rules[$project])) {
			$rules = $this->__rules[$project];
		} else {
			$rules = $this->toArray();
		}

		if (empty($rules)) {
			return false;
		}

		$this->__rules[$project] = $rules;

		foreach ((array)$rules as $rule => $perms) {
			if ($rule === $path) {

				$check = null;

				if (isset($perms['*'])) {
					$check = $perms['*'];
				}

				if(isset($perms['@' . $group])) {
					$check .= $perms['@' . $group];
				}

				if (isset($perms[$user])) {
					$check .= $perms[$user];
				}

				if ($check && strpos($check, $access) !== false) {
					return true;
				}

				break;
			}
		}
		return false;
	}

	function groups($project = null) {
		if (empty($project)) {
			$config = Configure::read('Project');
			$project = $config['url'];
		}

		if (!empty($this->__rules[$project])) {
			$rules = $this->__rules[$project];
		} else {
			$rules = $this->toArray();
		}

		if (empty($rules['groups'])) {
			return false;
		}

		$result = array();
		foreach ((array)$rules['groups'] as $group => $users) {
			$result[]['Group'] = array(
				'name' => $group,
				'users' => array_map('trim', explode(',', $users))
			);
		}

		return $result;
	}

	function toArray() {
		$string = $this->file();
		$lines = explode("\n", $string);

		$result = array();

		foreach ($lines as $line) {
			$data = trim($line);
			$first = substr($data, 0, 1);

			if ($first != ';' && $data != '') {
				if ($first == '[' && substr($data, -1, 1) == ']') {
					$section = preg_replace('/[\[\]]/', '', $data);
				} else {
					$delimiter = strpos($data, '=');

					if ($delimiter > 0) {
						$key = strtolower(trim(substr($data, 0, $delimiter)));
						$value = trim(substr($data, $delimiter + 1));

						if (substr($value, 0, 1) == '"' && substr($value, -1) == '"') {
							$value = substr($value, 1, -1);
						}
						$result[$section][$key] = stripcslashes($value);
					} else {
						if (!isset($section)) {
							$section = '';
						}
						$resule[$section][strtolower($data)] = '';
					}
				}
			}
		}

		return $result;
	}

	function file() {
		$File = $this->__getFile();
		return $File->read();
	}

	function &__getFile() {
		$config = Configure::read('Project');
		$repoType = $config['repo']['type'];
		$File = new File($config['repo']['path'] . DS . 'permissions.ini', true, 0777);
		return $File;
	}

}
?>