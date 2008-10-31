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
	function saveFile($data = array()) {
		$this->set($data);
		$File = $this->__getFile();
		$File->create();

		if (!$File->exists() || !$File->writable()) {
			return false;
		}

		$config = $this->config();

		if (empty($this->data['Permission']['fine_grained'])) {
			$repo = $config['url'] . ':/';
			if ($config['repo']['type'] == 'git') {
				$repo = 'refs/heads/master';
			}
			if (empty($this->data['Permission']['user_id'])) {
				$this->data['Permission']['user_id'] = 1;
			}

			$this->User->id = $this->data['Permission']['user_id'];
			$username = $this->User->field('username');
			ob_start();
			include(CONFIGS . 'templates' . DS . $config['repo']['type'] . DS . 'permissions.ini');
			$this->data['Permission']['fine_grained'] = ob_get_clean();
		}

		return $File->write(trim($this->data['Permission']['fine_grained']));
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function config() {
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
		$defaults = array('access' => 'w', 'user' => null, 'group' => null, 'project' => null, 'default' => false);
		extract(array_merge($defaults, $options));

		$rules = $this->rules($project);

		if (empty($rules)) {
			return true;
		}

		if ($project === null) {
			$config = $this->config();
			$project = $config['url'];
		}

		if (empty($rules[$project])) {
			return $default;
		}

		foreach ((array)$rules[$project] as $rule => $perms) {
			if (ltrim($rule, '/') == ltrim($path, '/')) {

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
		if ($project !== null) {
			if (!empty($this->__rules[$project])) {
				return $this->__rules[$project];
			} else {
				return array();
			}
		}

		$project = 1;
		$config = $this->config();

		$parent = array($project => array(), 'groups' => array());

		if ($config['id'] !== 1) {
			$project = $config['url'];
			if (!empty($this->__rules[1])) {
				$parent = $this->__rules[1];
			} else {
				if ($file = $this->file(true)) {
					$parent = $this->__rules[1] = $this->toArray($file);
				}
			}
		}

		$rules = $this->toArray($this->file());

		if (empty($rules)) {
			return array();
		}

		if (!empty($parent[$project])) {
			$rules[$project] = array_merge($rules[$project], $parent[$project]);
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
						$key = strtolower(trim(substr($data, 0, $delimiter)));
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
						$result[$project][$section][strtolower($data)] = '';
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
		$repoType = strtolower($config['repo']['type']);
		if ($config['id'] == 1 || $root === true) {
			$path = Configure::read("Content.{$repoType}") . 'repo' . DS;
		}
		$File = new File($path . 'permissions.ini');
		return $File;
	}
}
?>