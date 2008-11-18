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

	var $__config = array();
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function saveFile($config = array()) {
		if (empty($config['repo'])) {
			$this->set($config);
			$config = $this->config();
		} else {
			$config = $this->config($config);
		}

		if (!is_dir($config['repo']['path'])) {
			$Folder = new Folder($config['repo']['path'], true, 0775);
		}

		$File = $this->__getFile();
		$File->create();

		if (!$File->exists() || !$File->writable()) {
			return false;
		}

		if (empty($this->data['Permission']['fine_grained']) && empty($this->data['Permission']['username'])) {
			return false;
		}

		if (empty($this->data['Permission']['fine_grained'])) {
			$repo = $config['url'] . ':/';
			if ($config['repo']['type'] == 'git') {
				$repo = 'refs/heads/master';
			}
			$username = $this->data['Permission']['username'];
			ob_start();
			include(CONFIGS . 'templates' . DS . $config['repo']['type'] . DS . 'permissions.ini');
			$this->data['Permission']['fine_grained'] = ob_get_clean();
		}

		$result = $File->write(trim($this->data['Permission']['fine_grained']));
		$this->data = array();
		return $result;
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function config($config = array()) {
		if (!empty($config)) {
			return $this->__config = array_merge($this->__config, $config);
		}
		if (empty($this->__config)) {
			$this->__config = Configure::read('Project');
		}
		return $this->__config;
	}

/**
 * undocumented function
 *
 * @return void
 *
 **/
	function check($path, $options = array()) {
		$defaults = array('user' => null, 'group' => null, 'project' => null, 'default' => false);
		extract(array_merge($defaults, $options));

		$rules = $this->rules($project);

		if (empty($rules)) {
			return $default;
		}

		if ($project === null) {
			$config = $this->config();
			$project = $config['url'];
		}

		if (empty($rules[$project]) || empty($access)) {
			return $default;
		}

		foreach ((array)$rules[$project] as $rule => $perms) {

			$isMatch = ltrim($rule, '/') == ltrim($path, '/');

			/* for multi paths
			$paths = explode('/', $path);

			if (strpos($rule, '/') !== false) {
				if (substr($rule, -1) == '*') {

				}
			}
			*/
			if ($isMatch) {

				$check = null;

				if (isset($perms['*'])) {
					$check = $perms['*'];
				}

				if (!empty($rules['groups'])) {
					foreach ($rules['groups'] as $agroup => $users) {
						if (in_array($user, $users)) {
							if(isset($perms['@' . $agroup])) {
								$check .= $perms['@' . $agroup];
								break;
							}
						}
					}
				}

				if(isset($perms['@' . $group])) {
					$check .= $perms['@' . $group];
				}

				if (isset($perms[$user])) {
					$check .= $perms[$user];
				}

				if (is_string($access)) {
					$access = array($access);
				}

				foreach ($access as $perm) {
					if ($check && strpos($check, $perm) !== false) {
						return true;
						return compact('check', 'perm', 'user', 'group');
					}
				}
				return false;
			}
		}
		return $default;
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function groups($rules = null) {
		if ($rules === null) {
			$rules = $this->rules();
		}

		if (empty($rules['groups'])) {
			return false;
		}

		$result = array();
		foreach ((array)$rules['groups'] as $group => $users) {
			$result[]['Group'] = array(
				'name' => $group,
				'users' => $users
			);
		}

		return $result;
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function rules($project = null) {
		$config = $this->config();

		if ($project === null) {
			$project = $config['url'];
		}

		if (!empty($this->__rules[$project])) {
			return $this->__rules[$project];
		}

		$parent = array($project => array(), 'groups' => array());

		if ($config['id'] != 1) {
			if (!empty($this->__rules[1])) {
				$parent = $this->__rules[1];
			} else {
				if ($file = $this->file(true)) {
					$this->__rules[1] = $parent = $this->toArray($file);
				}
			}
		}

		$rules = $this->toArray($this->file());

		if (empty($rules[$project])) {
			$rules[$project] = array();
		}

		if (!empty($parent[$project])) {
			$rules[$project] = Set::merge($rules[$project], $parent[$project]);
		}

		if (!empty($rules['groups']) && !empty($parent['groups'])) {
			$rules['groups'] = array_merge($rules['groups'], $parent['groups']);
		}
		return $this->__rules[$project] = $rules;
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function toArray($string = null) {
		if (!$string) {
			return array();
		}
		$config = $this->config();

		$result = array();
		$lines = explode("\n", $string);
		foreach ($lines as $line) {
			$data = trim($line);
			$first = substr($data, 0, 1);
			if ($first != ';' && $data != '') {
				if ($first == '[' && substr($data, -1, 1) == ']') {
					$project = $config['url'];
					$section = preg_replace('/[\[\]]/', '', $data);
					if (strpos($section, ':') !== false) {
						list($project, $section) = explode(':', $section);
					}
				} else if (!empty($project) && !empty($section)) {
					$delimiter = strpos($data, '=');

					if ($delimiter > 0) {
						$key = trim(substr($data, 0, $delimiter));
						$value = trim(substr($data, $delimiter + 1));

						if (substr($value, 0, 1) == '"' && substr($value, -1) == '"') {
							$value = substr($value, 1, -1);
						}
						if ($section == 'groups') {
							$result[$section][$key] = array_map('trim', explode(',', stripcslashes($value)));
						} else {
							$result[$project][$section][$key] = stripcslashes($value);
						}
					} else {
						if (!isset($section)) {
							$section = '';
						}
						$result[$project][$section][$data] = '';
					}
				}
			}
		}
		return $result;
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function file($root = false) {
		$File = $this->__getFile($root);
		if (!$File->exists() || !$File->readable()) {
			if (!$File->create()) {
				return false;
			}
		}
		return $File->read();
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function &__getFile($root = false) {
		$config = $this->config();
		$path = $config['repo']['path'] . DS;
		if ($config['id'] == 1 || $root === true) {
			$path = Configure::read("Content.base");
		}
		$File = new File($path . 'permissions.ini');
		return $File;
	}
}
?>