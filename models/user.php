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
				'rule' => '/^[\-_\.a-zA-Z0-9]{3,}$/',
				'required' => true,
				'message' => 'Required: Minimum three (3) characters, letters (no accents), numbers and .-_ permitted.'
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

	var $hasOne = array('Permission');

	var $SshKey = null;

	function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->SshKey = ClassRegistry::init('SshKey');
	}

	function beforeSave() {

		if (!empty($this->data['SshKey']['content'])) {
			$this->SshKey->set(array(
				'username' => $this->data['User']['username'],
			));

			return $this->SshKey->save($this->data['SshKey']);
		}

		if (!empty($this->data['Key']) && !empty($this->data['User']['username'])) {
			foreach ($this->data['Key'] as $type => $keys) {
				foreach ($keys as $key) {
					if (!empty($key['chosen'])) {
						$delete[$type][] = $key['content'];
					}
				}
			}

			foreach ($delete as $type => $keys) {
				$result = $this->SshKey->delete(array(
					'type' => $type, 'username' => $this->data['User']['username'],
					'content' => $keys
				));
			}
		}

		return true;
	}

	function afterSave($created) {

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

	function forgotten($data = array()) {
		$this->set($data);
		$this->recursive = -1;

		if (!empty($this->data['User']['username']) && empty($this->data['User']['email'])) {
			$this->data = $this->find(array('username' => $this->data['User']['username']));
		} else {
			$this->data = $this->find(array('email' => $this->data['User']['email']));
		}

		if (empty($this->data['User']['email'])) {
			$this->invalidate('email', 'Email could not be found');
			return false;
		}
		$this->data['User']['token'] = String::uuid();
		$this->data['User']['token_expires'] = date('Y-m-d', strtotime('+ 1 day'));

		$this->id = $this->data['User']['id'];
		if ($data = $this->save($this->data)) {
			return $data;
		}
		$this->invalidate('email', 'Email could not be sent');
		return false;
	}

	function verify($data = array()) {
		$this->set($data);

		if (empty($this->data['User']['token'])) {
			$this->invalidate('token', 'token could not be found');
			return false;
		}
		$this->recursive = -1;
		$this->data = $this->find(array('token' => $this->data['User']['token']));

		if (empty($this->data['User']['email'])) {
			$this->invalidate('token', 'token does not match');
			return false;
		}

		$password = $this->__generatePassword();
		$this->data['User']['tmp_pass'] = Security::hash($password, null, true);
		$this->data['User']['token'] = null;
		$this->data['User']['token_expires'] = null;

		$this->id = $this->data['User']['id'];
		if ($data = $this->save($this->data)) {
			$data['User']['tmp_pass'] = $password;
			return $data;
		}

		$this->invalidate('password', 'Password could not be reset');
		return false;
	}

	function __generatePassword($length = 10) {
		srand((double)microtime() * 1000000);
		$password = '';
		$vowels = array('a', 'e', 'i', 'o', 'u');
		$cons = array('b', 'c', 'd', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'r', 's', 't', 'u', 'v', 'w', 'tr', 'cr', 'br', 'fr', 'th', 'dr', 'ch', 'ph', 'wr', 'st', 'sp', 'sw', 'pr', 'sl', 'cl');
		for ($i = 0; $i < $length; $i++) {
			$password .= $cons[mt_rand(0, 31)] . $vowels[mt_rand(0, 4)];
		}
		return substr($password, 0, $length);
	}
}
?>