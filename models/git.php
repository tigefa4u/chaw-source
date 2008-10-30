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
class Git extends Object {

	var $useTable = false;

	var $__config = array('git' => 'git', 'tmp' => TMP, 'username' => '', 'password' => '');

	var $debug = array();

	var $response = array();

	var $working = null;

	var $repo = null;
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function config($config = array()) {
		$this->__config = array_merge($this->__config, $config);

		if (!empty($this->__config['repo'])) {
			$this->repo = $this->__config['repo'];
		}

		if (!empty($this->__config['working'])) {
			$this->working = $this->__config['working'];
		}
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function create($project, $options = array()) {
		extract(array_merge($this->__config, $options));

		if ($repo === null) {
			$repo = $this->repo;
		}

		if ($working === null) {
			$working = $this->working;
		}

		$repo = Folder::slashTerm($repo);
		$working = Folder::slashTerm($working);

		if (!is_dir($repo)) {
			$GitRepo = new Folder($repo, true, 0777);
		}
		if (!is_dir($working)) {
			$GitWorking = new Folder($working, true, 0777);
		}

		if (!is_dir($repo . $project . '.git')) {
			$Project = new Folder($repo . $project . '.git', true, 0777);
		}

		if (is_dir($repo . $project . '.git') && !file_exists($repo . $project . '.git' . DS . 'config')) {
			$this->call("--bare init", array('path' => $repo . $project . '.git'));
		}

		$this->config(array(
			'repo' => $repo . $project . '.git',
			'working' => $working . $project
		));

		if (!is_dir($working . $project)) {
			$this->pull();
		}

		if (!empty($options['remote'])) {
			$remote = $options['remote'];
		} else {
			$remote = "git@git.chaw";
		}

		$this->sub("remote add origin {$remote}:{$project}.git");
		$this->execute("cd {$this->working} && touch .gitignore");
		$this->call("add .");
		$this->call("commit", array("-m", '"Initial Project commit"'));
		$this->sub("--bare update-server-info");
		$this->push();
		$this->update();

		if (is_dir($this->repo) && is_dir($this->working)) {
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
		return $this->call("push", array($branch1, $branch2));
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function update($branch = 'master') {
 		return $this->call('pull', array($this->repo, $branch));
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function pull($branch = 'master', $params = array()) {

		if (!is_dir($this->repo)) {
			return false;
		}

		if (!is_dir($this->working)) {
			$this->run('clone', array_merge($params, array($this->repo, $this->working)));
			chmod($this->working, 0777);
		}

		if (is_dir($this->working)) {
			$this->call('checkout', array($branch));
			$this->update($branch);
			return $this->response;
		}

		return false;
	}

/**
 * undocumented function
 *
 * @return void
 *
 **/
	function commit($newrev) {
		/*
		$info = $this->info(array_pop(explode("/", $refname)), array("--pretty=format:'%H::%an::%ai::%s'", "--max-count=1"));

		$revision = array_pop(explode(" ", $info[0]));
		list($author, $commit_date, $message) = explode('::', $info[1]);

		$diff = $this->sub('diff', array($oldrev, $newrev));


		*/
		//$info = $this->sub('log', array("-p", "-1", "--pretty=format:%H::%an::%ai::%s", "--full-diff", "--unified", array_pop(explode("/", $refname))));

		$info = $this->sub('show', array($newrev, "--pretty=format:%H::%an::%ai::%s"));

		list($revision, $author, $commit_date, $message) = explode('::', $info[0]);
		unset($info[0]);

		$changes = array();

		$diff = join("\n", $info);

		$data['Git'] = compact('revision', 'author', 'commit_date', 'message', 'changes', 'diff');
		return $data;
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function diff() {

	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function info($branch, $params = null) {
		//$params = '%h %an %ar %s';

		if ($params === null) {
			$params = array('--header', '--max-count=1', $branch);
		} else if (is_array($params)) {
			array_push($params, $branch);
		} else {
			$params = array("--pretty=format:'{$params}'", $branch);
		}

		$out = $this->sub('rev-list', $params);

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
		$out = $this->sub('ls-tree', $params);

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
 * Creates an Git hook
 *
 * @param string $name values (applypatch-msg, commit-message, post-commit, post-receive, post-update, pre-applypatch, pre-commit, pre-rebase, update)
 * @param string $data location of the repository
 * @return void
 *
 **/
	function hook($name, $data = null, $options = array()) {
		extract(array_merge($this->__config, $options));

		if ($repo === null) {
			$repo = $this->repo;
		}

		$repo = Folder::slashTerm($repo);

		$Hook = new File($repo . 'hooks' . DS . $name, true, 0777);

		chmod($Hook->pwd(), 0777);

		if (!is_string($data) || $data === null) {
			extract($data);
			if (file_exists(CONFIGS . 'templates' . DS . 'git' . DS . 'hooks' . DS . $name)) {
				ob_start();
				include(CONFIGS . 'templates' . DS . 'git' . DS . 'hooks' . DS . $name);
				$data = ob_get_clean();
			}
		}

		if (empty($data)) {
			return false;
		}

		if ($Hook->append($data)) {
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
	function pathInfo($path = null) {
		$info = $this->call('log', array("--pretty=medium", '-1', '--', $path));
		$info = explode("\n", $info);

		$result['revision'] = (!empty($info[0])) ? trim(array_shift($info), 'commit ') : null;

		$result['author'] = (!empty($info[1])) ? trim(array_shift($info), "Author: ") : null;
		$result['date'] = (!empty($info[2])) ? trim(array_shift($info), "Date: ") : null;
		$result['message'] = (!empty($info)) ? trim(join("\n", $info)) : null;

		return $result;
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function call($command, $options = array(), $return = false) {
		extract($this->__config);
		$options = array_map('escapeshellcmd', (array)$options);

		$path = $this->working;
		if (!empty($options['path'])) {
			$path = $options['path'];
			unset($options['path']);
		}

		$c = trim("cd {$path} && {$git} {$command} " . join(' ', (array)$options));
		if ($return === true) {
			return $c;
		}
		return $this->execute($c);
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function run($command, $options = array(), $return = false) {
		extract($this->__config);
		$options = array_map('escapeshellcmd', $options);

		$c = trim("GIT_DIR={$this->repo} {$git} {$command} " . join(' ', (array)$options));
		if ($return === true) {
			return $c;
		}
		return $this->execute($c);
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function sub($command, $options = array(), $return = false) {
		extract($this->__config);
		$options = array_map('escapeshellcmd', (array)$options);

		$c = trim("GIT_DIR={$this->repo} {$git} {$command} " . join(' ', (array)$options));
		if ($return === true) {
			return $c;
		}
		umask(0);
		exec($c, $response);
		$this->debug[] = $c;
		$this->response = array_merge($this->response, $response);
		return $response;
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function execute($command) {
		$this->debug[] = $command;
		umask(0);
		$response = shell_exec($command);
		$this->response = array_merge($this->response, (array)$response);
		return $response;
	}

}
?>