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
/**
 * Base class for various repo types
 *
 * @package			chaw.plugins.Repo
 * @subpackage		chaw.plugins.models
 *
 **/
class Repo extends Overloadable {
/**
 * configuration
 *
 * @var string
 **/
	var $config = array(
		'class' => 'Git', 'type' => 'git', 'path' => null, 'working' => null,
		'username' => '', 'password' => '', 'chmod' => 0755
	);
/**
 * Type of Repo
 *
 * @var string
 **/
	var $_commands = array();
/**
 * Type of Repo
 *
 * @var string
 **/
	var $type = 'git';
/**
 * Type of Repo
 *
 * @var string
 **/
	var $path = null;
/**
 * Type of Repo
 *
 * @var string
 **/
	var $working = null;
/**
 * undocumented class variable
 *
 * @var string
 **/
	var $debug = array();
/**
 * undocumented class variable
 *
 * @var string
 **/
	var $response = array();
/**
 * undocumented class variable
 *
 * @var string
 **/
	var $useTable = false;
/**
 * undocumented class variable
 *
 * @var string
 **/
	var $_before = array();
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function __construct($config = array()) {
		$this->config($config);
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function config($config = array()) {
		if (!empty($config['alias']) && empty($config['type'])) {
			$config['type'] = $config['alias'];
		}
		$config = array_merge($this->config, (array)$config);
		$this->type = $config['type'] = strtolower($config['type']);
		$this->path = $config['path'] = rtrim($config['path'], '\/');
		$this->working = $config['working'] = rtrim($config['working'], '\/');
		return $this->config = $config;
	}
/**
 * Magic methods
 *
 * @return void
 *
 **/
	function call__($method, $params = array()) {
		if (method_exists($this, "_{$method}")) {
			$finder = "_{$method}";
			$command = array_shift($params);
			$args = array_shift($params);
			return $this->$finder($command, $args, $params);
		} else if (in_array($method, $this->_commands)){
			$args = array_shift($params);
			$return = array_pop($params);
			return $this->run($method, $args, $return);
		} else {
			trigger_error('method ' . $method . ' does not exist');
		}
		return false;
	}
/**
 * Set multiple commands to be run before will be joined with &&
 *
 * @param mixed command single command string or array of commands
 * @return void
 *
 **/
	function before($command = array()) {
		if (is_string($command)) {
			$command = array($command);
		}
		$this->_before = array_merge($this->_before, $command);
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
		return $this->execute("{$type} {$command}", $args, $return);
	}
/**
 * Executes given command with results based on return type
 *
 *
 * @param string $command - the command to run
 * @param mixed $args as array - the arguments for the command, as string - the return type
 * @param string $return
 * false - will use shell_exec() and return a string
 * true - will return the command
 * capture - will use exec() and return an array
 * pass - will use passthru() and return binary type
 *
 * @return mixed
 *
 **/
	function execute($command, $args = array(), $return = false) {
		$before = (!empty($this->_before)) ? trim(join(' && ', $this->_before)) . ' && ' : null;
		$this->_before = array();

		if (is_string($args)) {
			$args = array($args);
		}
		$args = array_map('escapeshellcmd', (array)$args);

		$c = trim("{$before}{$command} " . join(' ', (array)$args) . " " . $this->_credentials());

		if ($return === true) {
			return $c;
		}
		$this->debug[] = $c;

		umask(0);
		switch ($return) {
			case 'capture':
				exec($c, $response);
			break;
			case 'pass':
			case 'passthru':
				passthru($c, $response);
			break;
			default:
				$response = shell_exec($c);
			break;
		}

		$this->response = array_merge($this->response, (array)$response);
		return $response;
	}
/**
 * Create the parent folders for a repository
 *
 * @return void
 *
 **/
	function _create($options = array(), $return = false) {
		extract(array_merge($this->config, $options));

		$path = dirname($path);
		$working = dirname($working);

		if (!is_dir($path)) {
			$Parent = new Folder($path, true, $chmod);
		}
		if (!is_dir($working)) {
			$Working = new Folder($working, true, $chmod);
		}

		if (is_dir($path) && is_dir($working)) {
			return true;
		}

		return false;
	}
/**
 * Creates a hook
 *
 * @param string $name
 * GIT
 * applypatch-msg, commit-message, post-commit, post-receive, post-update,
 * pre-applypatch, pre-commit, pre-rebase, update)
 *
 * SVN
 * post-commit, post-lock, post-revprop-change, post-unlock, pre-commit, pre-lock,
 * pre-revprop-change, pre-unlock, start-commit
 *
 * @param string $data location of the repository
 * @return void
 *
 **/
	function _hook($name, $data = null, $options = array()) {
		extract($this->config);
		$Hook = new File($path . DS . 'hooks' . DS . $name, true, $chmod);
		chmod($Hook->pwd(), $chmod);

		if (!is_string($data) || $data === null) {
			extract((array)$data);
			if (file_exists(CONFIGS . 'templates' . DS . $type . DS . 'hooks' . DS . $name)) {
				ob_start();
				include(CONFIGS . 'templates' . DS . $type . DS . 'hooks' . DS . $name);
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
	function _credentials() {
		return null;
	}
}