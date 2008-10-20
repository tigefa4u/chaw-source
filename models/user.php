<?php
class User extends AppModel {

	var $name = 'User';

	var $displayField = 'username';

	var $validate = array();

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
		if (!empty($this->data['User']['ssh_key'])) {
			$path = Configure::read('Content.git') . 'repo' . DS . '.ssh' . DS . 'authorized_keys';
			$File = new File($path);
			$data = trim($this->data['User']['ssh_key']) . "\n";
			$File->append($data);
		}
	}
}
?>