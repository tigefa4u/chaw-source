<?php
App::import('Overloadable');

class SvnComponent extends Overloadable {

/* configuration settings
 *
 * svn: the system path to svn defaut: svn
 * tmp: tmp directory default: TMP
 * path: file or directory path
 * username: you know what that is
 * password: another obvious one
 *
 */

	var $config = array('svn' => 'svn', 'tmp' => TMP, 'path' => 'file://repo', 'username' => '', 'password' => '');

	var $__commands = array(
						//standard commands
						'add', 'annotate', 'blame', 'cat', 'checkout', 'cleanup', 'commit', 'copy', 'delete', 'diff', 'export', 'help',
						'import', 'info', 'list', 'log', 'merge', 'mkdir', 'move', 'praise', 'propdel', 'propedit', 'propget', 'proplist',
						'propset', 'remove', 'rename', 'resolved', 'revert', 'status', 'switch', 'update',
						//shortcuts
						'ann', 'co', 'ci', 'cp', 'del', 'rm', 'ls', 'mv', 'ren', 'pdel', 'pd', 'pedit', 'pe', 'pget', 'pg', 'plist', 'pl',
						'pset', 'ps', 'stat', 'st', 'sw', 'up'
					);

	var $__adminCommands = array('create', 'dump', 'help', 'hotcopy', 'load', 'lstxns', 'recover', 'rmtxns', 'setlog');

	var $__lookCommands = array('author', 'cat', 'changed', 'date', 'diff', 'dirs-changed', 'help', 'history', 'info', 'log', 'proplist', 'tree', 'youngest');

	var $__debug = array();
/**
 * call the svn method
 *
 *
 */
	function call__($method, $params) {
		extract($this->config);
		$extra = null;
		$count = count($params);
		if ($count == 1) {
			$path = rtrim($path, '/') . '/' . join('/', $params);;
		} else {
			$extra = join(' ', $params);
		}

		if (in_array($method, $this->__commands)) {
			$command = "{$svn} {$method} {$path}{$extra} --username {$username} --password {$password}";
		} else if (preg_match('/^(admin)([^.]+)/', $method, $matches)) {
			if (isset($matches[1]) && in_array($matches[1], $this->__adminCommands)) {
				$method = $matches[1];
				$command = "{$svn}admin {$method} {$path}{$extra} --username {$username} --password {$password}";
			}
		}else if (preg_match('/^(look)([^.]+)/', $method, $matches)) {
			if (isset($matches[1]) && in_array($matches[1], $this->__lookCommands)) {
				$method = $matches[1];
				$command = "{$svn}look {$method} {$path}{$extra} --username {$username} --password {$password}";
			}
		}

		if (empty($command)) {
			trigger_error("{$method} could not be found.", E_USER_ERROR);
			return false;
		} else {
			$this->__debug[] = $command;
			$result = shell_exec(trim($command));
			$last_line = system($command, $retval);
			var_dump($result);
			var_dump($last_line);
			var_dump($retval);
			return $result;
		}
	}
/**
* sets or returns config
*
*/
	function config($config = array()) {
		if (empty($config)) {
			return $this->config;
		}
		return $this->config = array_merge($this->config, $config);
	}
/**
* returns debug info
*
*/
	function trace($config = array()) {
		return $this->__debug;
	}
	
	function checkout() {
		
	}
	
	function blame() {
		
	}

/**
* callbacks that need to be overriden for call__
*
*/
	function initialize(&$controller) {}
	function startup(&$controller) {}
	function beforeRender() {}
	function beforeRedirect() {}
	function shutdown() {}
}