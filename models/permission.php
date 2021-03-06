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
class Permission extends AppModel {

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $name = 'Permission';

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $belongsTo = array('User', 'Project');

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $__rules = array();

	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $__config = array();

	/**
	 * undocumented function
	 *
	 * @param string $user
	 * @return void
	 */
	function user($user = null) {
		if (!is_numeric($user)) {
			$user = $this->User->field('id', array('username' => $user));
		}
		return $user;
	}

	/**
	 * undocumented function
	 *
	 * @param string $project
	 * @param string $user
	 * @return void
	 */
	function group($project, $user = null) {
		if (is_array($project)) {
			extract($project);
		}

		$user = $this->user($user);

		if (!$user || !$project) {
			return false;
		}

		return $this->field('group', array('project_id' => $project, 'user_id' => $user));
	}

	/**
	 * undocumented function
	 *
	 * @param string $config
	 * @return void
	 */
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

			if ($config['repo']['type'] == 'svn') {
				$perms = $this->find('all', array(
					'fields' => array('Permission.group', 'User.username'),
					'conditions' => array('Permission.project_id' => $config['id']),
					'recursive' => 0
				));
				$groups = array();
				if (!empty($perms)) {
					foreach ($perms as $perm) {
						$groups[$perm['Permission']['group']][] = $perm['User']['username'];
					}
				} else {
					$groups = array_flip(array_keys($this->Project->groups()));
				}
			}

			$username = $this->data['Permission']['username'];
			ob_start();
			include(CONFIGS . 'templates' . DS . $config['repo']['type'] . DS . 'permissions.ini');
			$this->data['Permission']['fine_grained'] = ob_get_clean();
		}
		$result = $File->write(trim($this->data['Permission']['fine_grained']));
		$this->data = array();
		$this->__rules = array();
		return $result;
	}

	/**
	 * undocumented function
	 *
	 * @param string $config
	 * @return void
	 */
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
	 * @param string $path
	 * @param string $options
	 * @return void
	 */
	function check($path, $options = array()) {
		$defaults = array(
			'user' => null, 'group' => null, 'access' => null,
			'project' => null, 'default' => false
		);
		$options = array_merge($defaults, $options);
		extract($options);

		if ($project === null) {
			$config = $this->config();
			$project = $config['url'];
		}

		$rules = $this->rules($project);

		if (!empty($rules[$project])) {

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

					if ($check) {
						foreach ((array)$access as $perm) {
							if (strpos($check, $perm) !== false) {
								return true;
							}
							if ($perm == 'c' || $perm == 'u' || $perm == 'd') {
								if (strpos($check, 'w') !== false) {
									return true;
								}
							}
							if ($perm == 'w') {
								if (strpos($check, 'c') !== false || strpos($check, 'u') !== false) {
									return true;
								}
							}
						}
						return false;
					}
				}
			}
		}

		if ($access == 'd') {
			return false;
		}
		return $default;
	}

	/**
	 * undocumented function
	 *
	 * @param string $rules
	 * @return void
	 */
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
	 * @param string $project
	 * @param string $atomic
	 * @return void
	 */
	function rules($project = null, $atomic = array()) {
		$config = $this->config();

		if ($project === null) {
			$project = $config['url'];
		}

		if (empty($this->__rules[$project])) {
			$parent = array($project => array(), 'groups' => array());

			if ($config['id'] != 1) {
				if (!empty($this->__rules[1])) {
					$parent = $this->__rules[1];
				} else {
					if ($file = $this->parent()) {
						$parent = $this->__rules[1] = $this->toArray($file);
					}

					if ($root = $this->root()) {
						$root = $this->toArray($root);
						$parent = $this->__rules[1] = Set::merge($parent, $root);
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

			$this->__rules[$project] = $rules;
		}

		if (!empty($atomic)) {
			$this->__rules[$project] = Set::merge($this->__rules[$project], array($project => $atomic));
		}

		return $this->__rules[$project];
	}

	/**
	 * undocumented function
	 *
	 * @param string $string
	 * @return void
	 */
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
	 */
	function fileExists() {
		$File = $this->__getFile();
		return $File->exists();
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function file() {
		$File = $this->__getFile();
		if (!$File->readable()) {
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
	 */
	function root() {
		$path = Configure::read("Content.base");
		$File = new File($path . 'permissions.ini');
		if (!$File->readable()) {
			return null;
		}
		return $File->read();
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function parent() {
		$config = $this->config();
		if($config['id'] == 1 || empty($config['fork'])) {
			return array();
		}
		if(!empty($config['fork'])) {
			$path = Configure::read("Content.{$config['repo']['type']}") . 'repo' . DS .  $config['url'] . '.git' . DS;
		}

		$File = new File($path . 'permissions.ini');
		if (!$File->readable()) {
			if (!$File->create()) {
				return false;
			}
		}
		return $File->read();
	}

	/**
	 * undocumented
	 *
	 */
	function &__getFile() {
		$config = $this->config();
		$path = $config['repo']['path'] . DS;
		if($config['id'] == 1) {
			$path = Configure::read("Content.base");
		}
		$File = new File($path . 'permissions.ini');
		return $File;
	}
}
?>