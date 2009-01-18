<?php
/**
 * Short description
 *
 * Long description
 *
 * Copyright 2008, Garrett J. Woodworth <gwoo@cakephp.org>
 * Redistributions not permitted
 *
 * @copyright		Copyright 2008, Garrett J. Woodworth
 * @package			chaw.plugins.Repo
 * @subpackage		chaw.plugins.models
 * @since			Chaw 0.1
 * @license			commercial
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
 * undocumented function
 *
 * @return void
 *
 **/
	var $gitDir = null;
/**
 * undocumented function
 *
 * @return void
 *
 **/
	var $branch = null;
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
			$this->cd($path);
			$this->run("--bare init");
		}

		$this->pull();

		if (!empty($options['remote'])) {
			$remote = $options['remote'];
			unset($options['remote']);
		} else {
			$remote = "git@git.chaw";
		}

		$project = basename($path);
		//$this->remote(array('add', 'origin', "{$remote}:{$project}"));

		if (is_dir($this->working) && !file_exists($this->working . DS . '.gitignore')) {
			$this->cd();
			$this->before(array("touch .gitignore"));
			$this->commit("Initial Project Commit");
			//$this->run("--bare", array('update-server-info'));
			$this->update();
			$this->push();
		}

		if (is_dir($path) && is_dir($this->working)) {
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
		$this->branch = null;
		$working = dirname(dirname($working));
		$project = basename($path);
		$fork = dirname($path) . DS . 'forks' . DS . $user . DS . $project;

		$this->config(array(
			'working' => $fork
		));

		if (is_dir($this->working)) {
			$this->config(array(
				'path' => $this->working,
				'working' => $working . DS . 'forks' . DS . $user . DS . str_replace('.git', '', $project)
			));
			$this->pull();
			return true;
		}

		$userDir = dirname($this->working);;
		if (!is_dir($userDir)) {
			$Fork = new Folder($userDir, true, $chmod);
		}

		$this->clone(array('--bare', $this->path, $this->working));

		if (is_dir($this->working)) {
			if (!empty($options['remote'])) {
				$remote = $options['remote'];
				unset($options['remote']);
			} else {
				$remote = "git@git.chaw";
			}
			$this->config(array(
				'path' => $this->working,
				'working' => $working . DS . 'forks' . DS . $user . DS . str_replace('.git', '', $project)
			));
			//$this->remote(array('add', 'origin', "{$remote}:forks/{$user}/{$project}"));
			$this->pull();
		}

		if (is_dir($this->path) && is_dir($this->working)) {
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
		if (!$name) {
			return false;
		}
		extract($this->config);

		$path = $this->working;
		$branch = basename($path);

		if ($name === $branch) {
			return $this->branch = $name;
		}

		if ($this->branch == $branch) {
			$path = dirname($this->working);
		}

		$path = $path . DS . $name;
		if (!is_dir($path)) {
			$base = dirname($path);
			if (!is_dir($base)) {
				$clone = new Folder($base, true, $chmod);
			}
			$this->run('clone', array($this->path, $path));
		}

		$this->cd($path);
		/*
		$this->before(array(
			$this->run('pull', null, true)
		));
		*/
		$this->checkout(array('-b', $name));

		//$this->before(array("cd {$path}"));
		//$this->run('branch', array($name), 'capture');
		if ($switch === true) {
			$this->config(array('working' => $path));
			return $this->branch = $name;
		}
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
		if (is_string($options)) {
			$options = array('-m', escapeshellarg($options));

		} else {
			if (!empty($options['path'])) {
				$path = $options['path'];
				unset($options['path']);
			}
		}

		if (!$this->branch) {
			$this->pull();
		}

		$this->cd();
		$this->before(array("{$type} add {$path}"));
		return $this->run('commit', $options);
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function push($remote = 'origin', $branch = 'master') {
		$this->cd();
		return $this->run('push', array($remote, $branch), 'capture');
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function update($remote = null, $branch = null, $params = array()) {
		$this->cd();
 		return $this->run('pull', array_merge($params, array($remote, $branch)), 'capture');
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function pull($remote ='origin', $branch = 'master', $params = array()) {
		extract($this->config);

		if (!is_dir($path)) {
			return false;
		}

		$this->branch($branch, true);
		if (is_dir($this->working)) {
			$this->update($remote, $branch);
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
	function merge($project, $fork = false) {
		$this->branch('master', true);
		//$this->update('origin', 'master');

		$remote = 'parent';
		if (strpos($project, '.git') === false) {
			$project = "{$project}.git";
		}
		if ($fork) {
			$remote = $fork;
			$project = "forks/{$fork}/{$project}";
		}

		$this->cd();
		$this->remote(array('add', $remote, Configure::read('Content.git') . 'repo' . DS . $project));

		$response = $this->update($remote, 'master', array('--squash'));

		if (!empty($response[3])) {
			if (strpos($response[3], 'failed') !== false) {
				return false;
			}
		}

		$this->commit("Merge from {$project}");
		$this->push('origin', 'master');
		$this->pull('origin', 'master');
		return $response;
	}
/**
 * find all revisions and return contents of read.
 * type: all, count, array()
 *
 * @return array
 *
 **/
	function find($type = 'all', $options = array()) {
		if ($type == 'branches') {
			$this->cd();
			$result = $this->run('remote show origin', null, 'capture');
			return array_values(array_filter(explode(" ", array_pop($result))));
		}

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
		$this->cd();
		if ($path) {
			$path = str_replace($this->working . DS, '', $path);
		}
		$info = $this->run('log', array("--pretty=format:%H%x00%an%x00%ai%x00%s", '-1', '--', escapeshellarg($path)));
		if (empty($info)) {
			return null;
		}
		list($revision, $author, $date, $message) = explode(chr(0), $info);
		return compact('revision', 'author', 'date', 'message');
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function delete() {
		$this->logResponse = true;
		if ($this->branch !== 'master') {
			$branch = $this->branch;
			$working = $this->working;
			$this->branch('master', true);
			$this->run('branch -D', array($branch));
			$this->cd();
			$this->run('remote prune origin');
		}
		$this->execute("rm -rf {$working}");
		if (!is_dir($working)) {
			return true;
		}
		return false;
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

		$gitDir = null;
		if (empty($this->_before) && empty($this->gitDir)) {
			$gitDir = "--git-dir={$this->path} ";
		}

		return parent::run("{$gitDir}{$command}", $args, $return);
	}
}
?>