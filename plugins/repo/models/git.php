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
 * @package			chaw.plugins.Repo
 * @subpackage		chaw.plugins.models
 * @since			Chaw 0.1
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 */
App::import('Model', 'repo.Repo');
/**
 * undocumented class
 *
 * @package default
 *
 **/
class Git extends Repo {
/**
 * available commands for magic methods
 *
 * @var array
 **/
	var $_commands = array(
		'clone', 'config', 'diff', 'status', 'log', 'show', 'blame', 'whatchanged',
		'add', 'rm', 'commit', 'pull', 'push', 'branch', 'checkout', 'merge', 'remote'
	);
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function create($options = array()) {
		parent::_create();
		extract($this->config);

		if (!is_dir($path)) {
			$Project = new Folder($path, true, 0775);
		}

		if (is_dir($path) && !file_exists($path . DS . 'config')) {
			$this->before(array("cd {$path}"));
			$this->response[] = $this->run("--bare init");
		}

		if (!is_dir($working)) {
			$this->response[] = $this->pull();
		}

		if (!empty($options['remote'])) {
			$remote = $options['remote'];
			unset($options['remote']);
		} else {
			$remote = "git@git.chaw";
		}

		$project = basename($path);
		$this->response[] = $this->remote(array('add', 'origin', "{$remote}:{$project}"));

		$this->before(array(
			"cd {$working}", "touch .gitignore"
		));
		$this->response[] = $this->commit(array("-m", "'Initial Project Commit'"));
		$this->response[] = $this->run("--bare", array('update-server-info'));
		$this->response[] = $this->push();
		$this->response[] = $this->update();

		if (is_dir($path) && is_dir($working)) {
			return true;
		}

		return false;
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function commit($options = array()) {
		extract($this->config);

		$path = '.';
		if (!empty($options['path'])) {
			$path = $options['path'];
			unset($options['path']);
		}

		$this->before(array(
			"cd {$working}", "{$type} add {$path}"
		));

		$this->response[] = $this->run('commit', $options);
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function fork($user = null, $options = array()) {
		if (!$user) {
			return false;
		}
		extract($this->config);

		$project = basename($path);
		$fork = dirname($path) . DS . 'forks' . DS . $user . DS . $project;

		$this->config(array(
			'working' => $fork
		));

		if ($this->pull('master', array('--bare'))) {
			if (!empty($options['remote'])) {
				$remote = $options['remote'];
				unset($options['remote']);
			} else {
				$remote = "git@git.chaw";
			}
			$this->config(array(
				'path' => $fork,
				'working' => dirname($working) . DS . 'forks' . DS . $user . DS . str_replace('.git', '', $project)
			));
			$this->response[] = $this->remote(array('add', 'origin', "{$remote}:forks/{$user}/{$project}"));
			$this->response[] = $this->pull();
		}

		if (is_dir($this->config['path']) && is_dir($this->config['working'])) {
			return true;
		}

		return false;
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function branch($name, $switch = false) {
		$this->before(array("cd {$this->working}"));
		$response = $this->response[] = $this->run('branch', array($name), 'capture');
		if ($switch === true) {
			$this->before(array("cd {$this->working}"));
			$this->checkout('new');
		}
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function push($remote = 'origin', $branch = 'master') {
		$this->before(array("cd {$this->working}"));
		return $this->response[] = $this->run('push', array($remote, $branch), 'capture');
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function update($remote = 'origin', $branch = 'master') {
		$this->before(array("cd {$this->working}"));
 		return $this->response[] = $this->run('pull', array($remote, $branch), 'capture');
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function pull($branch = 'master', $params = array()) {
		extract($this->config);

		if (!is_dir($path)) {
			return false;
		}

		if (!is_dir($working)) {
			$this->response[] = $this->run('clone', array_merge($params, array($path, $working)));
			chmod($working, $chmod);
		}

		if (is_dir($working)) {
			$this->before(array("cd {$working}"));
			$this->response[] = $this->run('checkout', array($branch));
			$this->response[] = $this->update($branch);
			return $this->response;
		}

		return false;
	}
/**
 * find all revisions and return contents of read.
 *
 * @return array
 *
 **/
	function find($type = 'all', $options = array()) {

		if (is_array($type)) {
			extract(array_merge(array('commit' => null), $type));

			$fieldMap = array(
				'hash' => '%H',
				'email' => '%ae',
				'author' => '%an',
				'committer' => '%cn',
				'committer_email' => '%ce',
				'subject' => '%s',
			);

			$format = '--pretty=format:';
			if (!empty($options)) {
				foreach((array)$options as $field) {
					$format .= $fieldMap[$field] . '%x00';
				}
			} else {
				foreach($fieldMap as $field => $code) {
					$options[] = $field;
					$format .= $code . '%x00';
				}
			}
			$data = $this->run('log', array($commit, $format, '-1'));
			if (!empty($data)) {
				return array_combine($options, array_filter(explode(chr(0), $data)));
			}
			return $data;
		}

		if (!empty($options['conditions']['path'])) {
			$options['path'] = $options['conditions']['path'];
			unset($options['conditions']['path']);
		}

		extract(array_merge(array('path' => '.', 'limit' => 100, 'page' => 1), $options));

		if (empty($path)) {
			return false;
		}

		$data = explode("\n", $this->run('log', array("--pretty=format:%H", '--', str_replace($this->working . '/', '', $path))));

		if ($type == 'count') {
			return count($data);
		}

		if ($type == 'all') {
			return parent::_findAll($data, compact('limit', 'page'));
		}
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function read($newrev = null, $diff = false) {
		if ($diff) {
			$info = $this->run('show', array($newrev, "--pretty=format:%H%x00%an%x00%ai%x00%s"), 'capture');
		} else {
			$info = $this->run('log', array($newrev, "--pretty=format:%H%x00%an%x00%ai%x00%s"), 'capture');
		}
		if (empty($info)) {
			return null;
		}
		list($revision, $author, $commit_date, $message) = explode(chr(0), $info[0]);
		unset($info[0]);

		$changes = array();

		if ($diff) {
			$diff = join("\n", $info);
		}

		$data = compact('revision', 'author', 'commit_date', 'message', 'changes', 'diff');
		return $data;
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function info($branch, $params = null) {
		if ($params === null) {
			$params = array('--header', '--max-count=1', $branch);
		} else if (is_array($params)) {
			array_push($params, $branch);
		} else {
			$params = array("--pretty=format:'{$params}'", $branch);
		}

		$out = $this->run('rev-list', $params, 'capture');

		return $out;
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function tree($branch, $params = array()) {
		if (empty($params)) {
			$params = array($branch, "| sed -e 's/\t/ /g'");
		} else {
			array_push($params, $branch);
		}
		$out = $this->run('ls-tree', $params, 'capture');

		if (empty($out[0])) {
			return false;
		}

		if (strpos(trim($out[0]), ' ') === false) {
			return $out;
		}

		$result = array();

        foreach ($out as $line) {
            $entry = array();
            $arr = explode(" ", $line);
            $entry['perm'] = $arr[0];
            $entry['type'] = $arr[1];
            $entry['hash'] = $arr[2];
            $entry['file'] = $arr[3];
            $result[] = $entry;
        }
        return $result;
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function pathInfo($path = null) {
		$this->before(array("cd {$this->working}"));
		$info = $this->run('log', array("--pretty=format:%H%x00%an%x00%ai%x00%s", '-1', '--', str_replace($this->working . '/', '', $path)));

		if (empty($info)) {
			return null;
		}
		list($revision, $author, $date, $message) = explode(chr(0), $info);
		return compact('revision', 'author', 'date', 'message');
	}
/**
 * Run a command specific to this type of repo
 *
 * @see execute for params
 * @return misxed
 *
 **/
	function run($command, $args = array(), $return = false) {
		extract($this->config);
		$before = null;
		if (empty($this->_before)) {
			$before = "GIT_DIR={$this->path} ";
		}
		return $this->execute("{$before}{$type} {$command}", $args, $return);
	}
}
?>