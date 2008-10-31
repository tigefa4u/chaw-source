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
class Svn extends Object {

	var $useTable = false;

	var $__config = array('svn' => 'svn', 'repo' => null, 'working' => null, 'username' => '', 'password' => '');

	var $debug = array();

	var $response = array();

	var $repo = null;

	var $working = null;

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
		return $this->__config;
	}
/**
 * Create a new repo; initialize the branches, tags, trunk; checkout a working copy to TMP
 *
 * @param string $project name of the project
 * @param array $options repo, working
 * @example $this->Svn->create('demo', array('repo' => TMP . 'svn/repo', 'working' => APP . 'working'));
 * @return void
 *
 **/
	function create($project, $options = array()) {
		extract($this->config($options));

		$repo = rtrim($repo, DS);
		$working = rtrim($working, DS);

		if (!is_dir(dirname($repo))) {
			$SvnRepo = new Folder(dirname($repo), true, 0777);
		}
		if (!is_dir(dirname($working))) {
			$SvnWorking = new Folder(dirname($working), true, 0777);
		}

		if (!is_dir($repo)) {
			$this->admin('create', $repo);
		}

		if (!is_dir($working . '/branches')) {
			$file = 'file://' . $repo;
			$this->sub('import', array(CONFIGS . 'templates' . DS . 'svn' .DS . 'project', $file, '--message "Initial project import"'));
			$this->sub('checkout', array($file, $working));
		}
		
		if (is_dir($repo) && is_dir($working . '/branches')) {
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
	function update($path = null) {
		if ($path === null) {
			$path = $this->working;
		}
		return $this->sub('update', array($path));
	}

/**
 * Get the info about a directory or file
 *
 * @param string $path path inside of working with preceeding /
 * @example $this->Svn->info('/branches');
 * @return void
 *
 **/
	function commit($revision) {
		$author = $this->look('author', array('-r', $revision));
		$commit_date = $this->look('date', array('-r', $revision));
		$message = $this->look('log', array('-r', $revision));
		$changed = $this->look('changed', array('-r', $revision));
		$diff = $this->look('diff', array('-r', $revision));

		/*
		$previous = ($revision > 1) ? $revision - 1 : 1;
		$diff = $this->sub('diff', array('-r', "{$previous}:{$revision}));
		*/
		// Parse the date nicely
		// FIXME: messy
		// TODO: use a nice reg exp
		$bits = explode(" ", $commit_date);
		$commit_date = $bits[0] . ' ' . $bits[1];

		// Put each file that has been changed into an array
		$temp = array();
		$temp = explode("\n", $changed);
		// Make the codes more readable
		// A  => Item Added
		// D  => Item Deleted
		// U  => Item Contents Updated
		// _U => Item Properties Updated
		// UU => Item Contents and Properties Updated

		foreach ($temp as $tmp) {
			if (empty($tmp)) {
				continue;
			}
			$bits = explode(" ", $tmp);

			$action = @$bits[0];
			$file = $bits[sizeof($bits)-1];

			switch ($action) {
				case 'A':
					$action = 'Added';
					break;
				case 'D':
					$action = 'Deleted';
					break;
				case 'U':
					$action = 'Updated';
					break;
				case '_U':
					$action = 'Updated';
					break;
				case 'UU':
					$action = 'Updated';
					break;
			}

			$changes[] = $action . ' ' . $file;

		}

		$data['Svn'] = compact('revision', 'author', 'commit_date', 'message', 'changes', 'diff');

		return $data;
	}
/**
 * Get the info about a directory or file
 *
 * @param string $path path inside of working with preceeding /
 * @example $this->Svn->info('/branches');
 * @return void
 *
 **/
	function info($path = null) {
		return $this->sub('info', array($this->working . $path));
	}
/**
 * Run svnadmin, Besides providing the ability to create Subversion repositories,
 * this program allows you to perform several maintenance operations on those repositories.
 *
 * @param string $command
 * @param mixed $params
 * @example $this->Svn->admin('create', '/path/to/new/repo');
 * @return void
 *
 **/
	function admin($command, $params) {
		extract($this->__config);
		if (is_string($params)) {
			$params = array($params);
		}
		$c = trim("{$svn}admin {$command} " . join(' ', $params) . ' ' . $this->__creds());
		return $this->execute($c);
	}
/**
 * Run svnlook, is a command-line utility for examining different aspects of a Subversion repository.
 * It does not make any changes to the repository—it's just used for “peeking”
 *
 * @param string $command
 * @param mixed $params
 * @example $this->Svn->look('author', 'file:///path/to/repo');
 * @return void
 *
 **/
	function look($command, $params) {
		extract($this->__config);
		if (is_string($params)) {
			$params = array($params);
		}
		$c = trim("{$svn}look {$command} {$this->repo} " . join(' ', $params) . ' ' . $this->__creds());
		return $this->execute($c);
	}
/**
 * Run svn subcommands
 *
 * @param string $command
 * @param mixed $params
 * @example $this->Svn->sub('checkout', array('file:///path/to/repo', /path/to/new/working/copy'));
 * @return void
 *
 **/
	function sub($command, $params) {
		extract($this->__config);
		if (is_string($params)) {
			$params = array($params);
		}
		$c = trim("{$svn} {$command} " . join(' ', $params) . ' ' . $this->__creds());
		return $this->execute($c);
	}

/**
 * Creates an SVN hook
 *
 * @param string $name values (post-commit, post-lock, post-revprop-change, post-unlock, pre-commit, pre-lock, pre-revprop-change, pre-unlock, start-commit)
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
			if (file_exists(CONFIGS . 'templates' . DS . 'svn' . DS . 'hooks' . DS . $name)) {
				ob_start();
				include(CONFIGS . 'templates' . DS . 'svn' . DS . 'hooks' . DS . $name);
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
	function pathInfo($path) {
		$data = $this->sub('log', array($path));

		$lines = explode("\n", $data);
		$info = (!empty($lines[3])) ? explode("|", $lines[1]) : array();

		$result['revision'] = (!empty($info[0])) ? trim($info[0], 'r') : null;
		$result['author'] = (!empty($info[1])) ? trim($info[1]) : null;
		$result['date'] = (!empty($info[2])) ? trim($info[2]) : null;
		$result['message'] = (!empty($lines[3])) ? trim($lines[3]) : null;

		return $result;
	}
/**
 * Execute any command
 *
 * @param string $command
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
/**
 * Get the config credentials
 *
 * @return void
 *
 **/
	function __creds() {
		extract($this->__config);
		if (!empty($username) && !empty($password)) {
			return "--username {$username} --password {$password}";
		}
		return null;
	}
}
?>