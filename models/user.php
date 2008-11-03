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
class User extends AppModel {

	var $name = 'User';

	var $displayField = 'username';

	var $validate = array(
		'username' => array(
			'allowedChars' => array(
				'rule' => '/^[\-_\.a-zA-Z0-9]{4,}$/',
				'required' => true,
				'message' => 'Required: Minimum four (4) characters, letters (no accents), numbers and .-_ permitted.'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'required' => true,
				'message' => 'Required: Username must be unique'
			)
		),
		'email' => array(
			'rule' => 'email',
			'required' => true,
			'message' => 'Required: Valid email address'
		),
		'password' => array(
			'rule' => 'alphaNumeric',
			'message' => 'Required: Alpha-numeric passwords only'
		)
	);

	var $hasMany = array('Permission');

	function permit() {
		if (!empty($this->data['User']['group'])) {
			$data = array('Permission' => array(
				'user_id' => $this->id,
				'project_id' => $this->data['User']['project_id'],
				'group' => $this->data['User']['group']
			));
			$this->Permission->save($data);
		}
	}

	function afterSave($created) {

		$this->saveKey();

		if (!empty($this->data['User']['project_id']) && empty($this->data['User']['group'])) {
			$this->Permission->create(array(
				'user_id' => $this->id,
				'project_id' => $this->data['User']['project_id'],
				'group' => $this->data['User']['group']
			));
			$this->Permission->save();
		}
	}

	function isUnique($data, $options = array()) {
		if (!empty($data['username'])) {
			$this->recursive = -1;
			if ($result = $this->findByUsername($data['username'])) {
				if ($this->id === $result['User']['id']) {
					return true;
				}
				return false;
			}
			return true;
		}
	}

	function saveKey($username = null, $key = null) {
		if ($username == null) {
			if (empty($this->data['User']['username'])) {
				return false;
			} else {
				$username = $this->data['User']['username'];
			}
		}
		if ($key == null) {
			if (empty($this->data['User']['ssh_key'])) {
				return false;
			} else {
				$key = $this->data['User']['ssh_key'];
			}
		}

		$path = Configure::read('Content.git') . 'repo' . DS . '.ssh' . DS . 'authorized_keys';
		$File = new File($path, true);

		if ($File->writable() !== true) {
			return false;
		}

		$new = 'command="../../chaw git_shell $SSH_ORIGINAL_COMMAND -user ' . $username. '",no-port-forwarding,no-X11-forwarding,no-agent-forwarding,no-pty '
			. str_replace(array("\n", "\r", "\t"), array("", "", ""), trim($key));

		$lines = array();
		$found = false;
		if ($keys = $File->read()) {
			$lines = explode("\n", $keys);
			foreach ($lines as $num => $line) {
				if (preg_match('/-user\s' . $username . '/', $line)) {
					$lines[$num] = $new;
					$found = true;
					break;
				}
			}
		}

		if (!$found) {
			$lines[] = $new;
		}

		return $File->write(join("\n", $lines));
	}
}
?>