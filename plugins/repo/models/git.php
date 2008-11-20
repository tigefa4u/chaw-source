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
			$this->run("--bare init");
		}

		if (!is_dir($working)) {
			$this->pull();
		}

		if (!empty($options['remote'])) {
			$remote = $options['remote'];
			unset($options['remote']);
		} else {
			$remote = "git@git.chaw";
		}

		$project = basename($path);
		$this->remote(array('add', 'origin', "{$remote}:{$project}"));

		$this->before(array(
			"cd {$working}", "touch .gitignore", "{$type} add ."
		));
		$this->commit(array("-m", "'Initial Project Commit'"));
		$this->run("--bare", array('update-server-info'));
		$this->push();
		$this->update();

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
			$this->remote(array('add', 'origin', "{$remote}:forks/{$user}/{$project}"));
			$this->pull();
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
	function push($branch1 = 'origin', $branch2 = 'master') {
		$this->before(array("cd {$this->working}"));
		return $this->run('push', array($branch1, $branch2), 'capture');
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function update($branch = 'master') {
		$this->before(array("cd {$this->working}"));
 		return $this->run('pull', array($this->path, $branch), 'capture');
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
			$this->run('clone', array_merge($params, array($path, $working)));
			chmod($working, $chmod);
		}

		if (is_dir($working)) {
			$this->before(array("cd {$working}"));
			$this->run('checkout', array($branch));
			$this->update($branch);
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
/*
	function find($type = 'all', $options = array()) {
		$youngest = trim($this->look('youngest'));
		$data = array();

		for($i = 1; $i <= $youngest; $i++) {
			$data[]'Repo'] = $this->read($i, false);
		}
		return $data;
	}
*/
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function read($newrev, $diff = false) {
		$info = $this->run('show', array($newrev, "--pretty=format:%H::::%an::::%ai::::%s"), 'capture');
		if (empty($info)) {
			return null;
		}
		list($revision, $author, $commit_date, $message) = explode('::::', $info[0]);
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
		$info = $this->run('log', array("--pretty=medium", '-1', '--', str_replace($this->working . '/', '', $path)));

		if (empty($info)) {
			return null;
		}
		$info = explode("\n", $info);

		$result['revision'] = (!empty($info[0])) ? trim(array_shift($info), 'commit ') : null;
		$result['author'] = (!empty($info[1])) ? str_replace("Author: ", "", array_shift($info)) : null;
		$result['date'] = (!empty($info[2])) ? date('Y-m-d H:m:s', strtotime(str_replace("Date: ", "", array_shift($info)))) : null;
		$result['message'] = (!empty($info)) ? trim(join("\n", $info)) : null;

		return $result;
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