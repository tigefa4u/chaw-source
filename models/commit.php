<?php
class Commit extends AppModel {

	var $name = 'Commit';

	var $belongsTo = array('User');

	var $hasOne = array(
		'Timeline' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('model' => 'Commit')
		)
	);

	function beforeSave() {
		if (!empty($this->data['Commit']['author'])) {
			$this->data['Commit']['user_id'] = $this->User->field('id', array('username' => $this->data['Commit']['author']));
		}
		if (!empty($this->data['Commit']['changes'])) {
			$this->data['Commit']['changes'] = serialize($this->data['Commit']['changes']);
		}
		return true;
	}

	function afterSave($created) {

		if ($created) {
			$timeline = array('Timeline' => array(
				'project_id' => $this->data['Commit']['project_id'],
				'model' => 'Commit',
				'foreign_key' => $this->id,
			));

			$this->Timeline->create($timeline);
			$this->Timeline->save();
		}
	}
}
?>