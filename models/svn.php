<?php
class Svn extends Object {

	var $name = 'Svn';

	var $useTable = false;

	var $__config = array('svn' => '/usr/bin/svn', 'tmp' => TMP, 'username' => '', 'password' => '');

	var $debug = array();

	var $workingCopy = null;

	var $repo = null;
/**
 * Create a new repo; initialize the branches, tags, trunk; checkout a working copy to TMP
 *
 * @param string $project name of the project
 * @param string $repo path to the new repository
 * @example $this->Svn->create('demo', TMP . 'svn/repo');
 * @return void
 *
 **/
	function create($project, $repo) {
		extract($this->__config);

		if (is_dir(dirname($repo))) {
			if (!is_dir($repo)) {
				$result = $this->admin('create', $repo);
			}

			if (is_dir($repo)) {

				$this->repo = $repo;

				$this->workingCopy = TMP . $project;

				if (!is_dir($this->workingCopy . '/branches')) {
					$file = 'file://' . rtrim($repo, '/') . '/' . $project;
					$result = $this->sub('import', array(TMP . 'svn/project', $file, '--message "Initial project import"'));
					$result = $this->sub('checkout', array($file, $this->workingCopy));
				}
			}
		}
	}
/**
 * Get the info about a directory or file
 *
 * @param string $path path inside of workingCopy with preceeding /
 * @example $this->Svn->info('/branches');
 * @return void
 *
 **/
	function info($path = null) {
		return $this->sub('info', array($this->workingCopy . $path));
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
		$c = $this->debug[] = trim("{$svn}admin {$command} " . join(' ', $params) . ' ' . $this->__creds());
		umask(0);
		return shell_exec($c);
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
		$c = $this->debug[] = trim("{$svn}look {$command} " . join(' ', $params) . ' ' . $this->__creds());
		umask(0);
		return shell_exec($c);
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
		$c = $this->debug[] = trim("{$svn} {$command} " . join(' ', $params) . ' ' . $this->__creds());
		umask(0);
		return shell_exec($c);
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