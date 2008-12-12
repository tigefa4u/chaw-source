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
 * @package			chaw
 * @subpackage		chaw.models
 * @since			Chaw 0.1
 * @license			commercial
 *
 */
class SshKey extends AppModel {

	var $name = 'SshKey';

	var $type = 'git';

	var $lines = array();

	var $user = array();

	var $_File = null;

	var $useTable = false;
/**
 * set properties
 *
 * @return void
 *
 **/
	function set($data = array()) {
		parent::set($data);

		if (empty($this->data['SshKey']['type'])) {
			return false;
		}
		$this->type = strtolower($this->data['SshKey']['type']);
		$path = Configure::read("Content.{$this->type}") . 'repo' . DS . '.ssh' . DS . 'authorized_keys';
		if (!empty($this->_File) && $this->_File->path == $path) {
			if ($this->_File->exists() != true) {
				$this->_File->create();
				$this->lines = $this->user = array();
			}
			return true;
		}
		$this->_File = new File($path, true);
		$this->lines = $this->user = array();
		return true;
	}
/**
 * save key to file if it does not exist
 *
 * @return void
 *
 **/
	function save($data = array()) {
		if ($this->set($data) === false) {
			return false;
		}

		if (empty($this->data['SshKey']['content']) || empty($this->data['SshKey']['username'])) {
			return false;
		}

		if ($this->_File->writable() !== true) {
			return false;
		}

		$key = $this->data['SshKey']['content'];
		$username = $this->data['SshKey']['username'];

		$new = str_replace(array("\n", "\r", "\t"), array("", "", ""), trim($key));

		$exists = false;
		$userKeys = $this->read();

		foreach ((array)$userKeys as $oldKey) {
			if ($oldKey == $new) {
				$exists = true;
				break;
			}
		}

		if (!$exists) {
			$this->lines[] = $this->command($username) . $new;
			$this->user[$username][] = $new;
		}

		return $this->write();
	}
/**
 * read lines from file
 * is username is present in $data, return only user keys
 * sets $lines to an array of contents in the file
 *
 * @return array
 *
 **/
	function read($data = array()) {
		if ($this->set($data) === false) {
			return false;
		}

		$hasUsername = $username = false;
		if (array_key_exists('username', $this->data['SshKey'])) {
			$hasUsername = true;
			$username = $this->data['SshKey']['username'];
		}

		if ($username && !empty($this->user[$username])) {
			return $this->user[$username];
		}

		if (!$username && !empty($this->lines)) {
			return $this->lines;
		}

		if ($this->_File->readable() !== true) {
			return false;
		}

		$this->_File->lock = null;
		if ($keys = $this->_File->read()) {
			$this->lines = explode("\n", $keys);
		}

		if ($username) {
			$this->user[$username] = array();
			if ($userKeys = preg_grep("/-user\s{$username}\"/", $this->lines)) {
				foreach ($userKeys as $line) {
					$this->user[$username][] = str_replace($this->command($username), "", $line);
				}
			}
		}

		if ($username) {
			return $this->user[$username];
		}

		if (!$hasUsername) {
			return $this->lines;
		}

		return array();
	}
/**
 * write lines to file
 *
 * @return void
 *
 **/
	function write() {
		$this->_File->lock = true;
		$result = $this->_File->write(join("\n", $this->lines), 'w', true);
		return $result;
	}
/**
 * delete a key
 *
 * @return void
 *
 **/
	function delete($data = array()) {
		if ($this->set($data) === false) {
			return false;
		}

		if (empty($this->data['SshKey']['content']) || empty($this->data['SshKey']['username'])) {
			return false;
		}

		if ($this->_File->writable() !== true) {
			return false;
		}

		$username = $this->data['SshKey']['username'];
		unset($this->data['SshKey']['username']);

		$keys = $this->data['SshKey']['content'];
		if (!is_array($keys)) {
			$keys = array($keys);
		}

		if (empty($this->lines)) {
			$this->read();
		}

		$oldKeys = array_flip($this->lines);

		$deleted = false;
		foreach ($keys as $key) {
			$key = $this->command($username) . str_replace(array("\n", "\r", "\t"), array("", "", ""), trim($key));
			if (isset($oldKeys[$key])) {
				unset($oldKeys[$key]);
				$deleted = true;
			}
		}

		if ($deleted === true) {
			$this->lines = array_keys($oldKeys);
			$this->user[$username] = array();
			return $this->write();
		}
		return true;
	}
/**
 * get command for a given user
 *
 * @param string $username
 * @return void
 *
 **/
	function command($type, $username = null) {
		if ($username === null) {
			$username = $type;
			$type = $this->type;
		} else {
			$type = strtolower($type);
		}
		return 'command="../../chaw ' . $type . '_shell $SSH_ORIGINAL_COMMAND -user ' . $username. '",no-port-forwarding,no-X11-forwarding,no-agent-forwarding,no-pty ';
	}
}
?>