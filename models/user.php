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

	//var $hasMany = array('Permission');

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
}
?>